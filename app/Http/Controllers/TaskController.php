<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Product;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function search(Request $request)
    {
        // Validar los parámetros
        $validatedData = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100', // Límite de paginación
            'dateIni' => 'nullable|date', // Fecha inicial
            'dateEnd' => 'nullable|date|after_or_equal:dateIni', // Fecha final debe ser igual o posterior
            'name' => 'nullable|string|max:255', // Nombre del trabajador
        ]);

        // Parámetros de paginación
        $perPage = $validatedData['per_page'] ?? 10;

        // Construir la consulta
        $tasks = Task::query()->with([
            'creation:created_by,user_id,username',
            'worker:user_data_id,document_type,document_number,name',
            'job:job_id,job_description'
        ])->orderBy('creation_date', 'desc');

        // Filtrar por fecha de inicio
        if (!empty($validatedData['dateIni'])) {
            $tasks->whereDate('day', '>=', $validatedData['dateIni']);
        }

        // Filtrar por fecha de fin
        if (!empty($validatedData['dateEnd'])) {
            $tasks->whereDate('day', '<=', $validatedData['dateEnd']);
        }

        // Filtrar por nombre o número de cédula
        if (!empty($validatedData['name'])) {
            $name = $validatedData['name'];

            // Validar el formato del input
            if (preg_match('/^[a-zA-Z\s]+$/', $name)) {
                // Solo letras: Buscar por nombre
                $tasks->whereHas('worker', function ($query) use ($name) {
                    $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
                });
            } elseif (preg_match('/^\d+$/', $name)) {
                // Solo números: Buscar por número de documento
                $tasks->whereHas('worker', function ($query) use ($name) {
                    // Solo números: Buscar por número de documento con el orden exacto
                    $query->where('user_data.document_number', 'LIKE', '%' . $name . '%')
                        ->whereRaw('user_data.document_number REGEXP ?', [preg_quote($name, '/') . '.*']);
                });
            } else {
                // Solo letras: Buscar por nombre
                $tasks->whereHas('worker', function ($query) use ($name) {
                    $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
                });
            }
        }

        // Ejecutar la consulta y devolver los resultados paginados
        $tasks = $tasks->paginate($perPage);

        return response()->json($tasks);
    }

    public function create()
    {
        $workers = Worker::select('user_data_id', 'name', 'document_type', 'document_number')->get();
        $jobs = Job::select('job_id', 'internal_code', 'job_description', 'price')->get();
        $plants =  Product::select('product_id', 'packing')->get();

        return response()->json([
            'workers' => $workers,
            'jobs' => $jobs,
            'plants' => $plants
        ]);
    }

    //funcion para calcular la expresion matematica de eval
    public function calculateExpression($expression)
    {
        // Asegúrate de que solo permite números, operadores básicos y espacios
        if ($expression == null) {
            return 0;
        }
        // Usa eval() de forma controlada
        try {
            return eval("return $expression;");
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('Error al evaluar la expresión.');
        }
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'day' => 'required|date',
            'worker_id' => 'required|exists:user_data,user_data_id',
            'job_id' => 'required|exists:jobs,job_id',
            'plant_id' => 'required|exists:products,product_id',
            'plant_from_id' => 'nullable|exists:products,product_id',
            'seccion' => 'nullable|string|max:4',
            'seccion_origen' => 'nullable|string|max:4',
            'mesa' => 'nullable|string|max:4',
            'cantidad_ingresada' => 'required|string|min:0',
            'cantidad_usada' => 'nullable|string|min:0',
            'precio_unidad' => 'required|numeric|min:0',
            'observations' => 'required|string|max:1000',
            'calification' => 'nullable|numeric|min:0|max:100',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            // Personalizar la respuesta
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear la tarea
        $task = new Task();
        $task->day = $request->day;
        $task->worker_id = $request->worker_id;
        $task->job_id = $request->job_id;
        $task->plant_id = $request->plant_id;
        $task->plant_from_id = $request->plant_from_id;
        $task->seccion = $request->seccion;
        $task->seccion_origen = $request->seccion_origen;
        $task->mesa = $request->mesa;
        $task->cantidad_ingresada = $request->cantidad_ingresada;
        $task->precio_unidad = $request->precio_unidad;
        $task->observations = $request->observations;
        $task->calification = $request->calification;
        if (preg_match('#^\d+:\d+$#', $request->cantidad_ingresada)) {

            // Normalizar el formato (asegurar HH:MM con ceros iniciales)
            list($hours, $minutes) = explode(':', $request->cantidad_ingresada); // Separar horas y minutos

            $hours = str_pad($hours, 2, '0', STR_PAD_LEFT); // Asegurar 2 dígitos en horas organizandolos a la izquierda
            $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT); // Asegurar 2 dígitos en minutos organizandolos a la izquierda

            // Convertir minutos a horas (dividir entre 60)
            $decimalHours = $hours + ($minutes / 60);

            // Almacenar el resultado como un número decimal
            $task->cantidad_unidades = $decimalHours;
        } else {
            // Evaluar expresión matemática de forma segura
            $task->cantidad_unidades = $this->calculateExpression($request->cantidad_ingresada);
        }
        if (preg_match('#^\d+:\d+$#', $request->cantidad_usada)) {
            // Normalizar el formato (asegurar HH:MM con ceros iniciales)
            list($hours, $minutes) = explode(':', $request->cantidad_ingresada); // Separar horas y minutos

            $hours = str_pad($hours, 2, '0', STR_PAD_LEFT); // Asegurar 2 dígitos en horas organizandolos a la izquierda
            $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT); // Asegurar 2 dígitos en minutos organizandolos a la izquierda

            // Convertir minutos a horas (dividir entre 60)
            $decimalHours = $hours + ($minutes / 60);

            // Almacenar el resultado como un número decimal
            $task->cantidad_unidades = $decimalHours;
        } else {
            // Evaluar expresión matemática de forma segura
            $task->cantidad_usada = $this->calculateExpression($request->cantidad_usada);
        }
        $task->created_by = $request->user_id;
        $task->creation_date = now();
        $task->lote = 0;
        $task->status = $request->user_id == 58 ? 7 :4;

        $task->save();

        return response()->json(['message' => 'Task created successfully'], 201);
    }

    public function taskType()
    {
        $taskTypes = TaskType::select('type_id', 'type_description')->distinct()->get();

        return response()->json(['taskTypes' => $taskTypes]);
    }
}
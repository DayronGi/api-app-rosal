<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Product;
use App\Models\Task;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TaskController extends Controller
{
    public function list(Request $request) {

           // Obtener parámetros para la paginación
           $perPage = $request->get('per_page');


        $tasks = Task::with([   'creation:created_by,user_id,username', 
                                'worker:user_data_id,document_type,document_number,name',
                                'job:job_id,job_description'])->paginate($perPage);

        return response()->json(['tasks' => $tasks]);
    }

    public function search(Request $request)
    {
        //obtener parametros de la paginación
        $perPage = $request->get('per_page');
        //obtener parametros de busqueda
        $dateIni = $request->get('dateIni');
        $dateEnd = $request->get('dateEnd');
        $name = $request->get('name');

        $tasks = Task::query()->with([  'creation:created_by,user_id,username',
                                        'worker:user_data_id,document_type,document_number,name', 
                                        'job:job_id,job_description']);

        // Filtrar por fecha de inicio  
        if ($dateIni) { 
            $tasks->whereDate('day', '>=', $dateIni); 
        }

        // Filtrar por fecha de fin
        if ($dateEnd) { 
            $tasks->whereDate('day', '<=', $dateEnd);
        }

        // Filtrar por nombre de trabajador
        if ($name) {
            $tasks->whereHas('worker', function ($query) use ($name) {
                $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
            });
        }

        // Ejecutar la consulta y devolver los resultados
            $tasks = $tasks->paginate($perPage);

        return response()->json($tasks);
    }
        
    

    public function create() {
        $workers = Worker::select('user_data_id', 'name', 'document_type', 'document_number')->get();
        $jobs = Job::select('job_id', 'internal_code', 'job_description', 'price')->get();
        $plants =  Product::select('product_id', 'packing')->get();

        return response()->json([
            'workers'=>$workers,
            'jobs'=>$jobs,
            'plants'=>$plants
        ]);
    }

    public function store(Request $request) {
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
        $task->cantidad_unidades = eval("return ".$request->cantidad_ingresada.";");
        $task->cantidad_usada = eval("return ".$request->cantidad_usada.";");
        $task->precio_unidad = $request->precio_unidad;
        $task->observations = $request->observations;
        $task->calification = $request->calification;
        $task->created_by = auth()->user()->user_id;
        $task->creation_date = now();
        $task->lote = 1;
        $task->status = 5;

        $task->save();

        return response()->json(['message' => 'Task created successfully']);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignedTask;
use Illuminate\Support\Facades\Validator;

class AssignedTaskController extends Controller
{
    public function search(Request $request)
    {
        //obtener parametros de la paginación
        $perPage = $request->get('per_page');
        //obtener parametros de busqueda
        $dateIni = $request->get('dateIni');
        $dateEnd = $request->get('dateEnd');
        $name = $request->get('name');

        $assignedtasks = AssignedTask::query()->where('status', '!=', 28)
            ->with([
                'worker:user_data_id,name,document_number,document_type',
                'creation:user_id,username',
                'department:department_id,department_name'
            ])->orderBy('creation_date', 'desc');

        // Filtrar por fecha de inicio
        if ($dateIni) {
            $assignedtasks->whereDate('start_date', '>=', $dateIni);
        }

        // Filtrar por fecha de fin
        if ($dateEnd) {
            $assignedtasks->whereDate('start_date', '<=', $dateEnd);
        }

        // Filtrar por nombre o número de cédula
        if (!empty($request->name)) {
            $name = $request->name;

            // Validar el formato del input
            if (preg_match('/^[a-zA-Z\s]+$/', $name)) {
                // Solo letras: Buscar por nombre
                $assignedtasks->whereHas('worker', function ($query) use ($name) {
                    $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
                });
            } elseif (preg_match('/^\d+$/', $name)) {
                // Solo números: Buscar por número de documento
                $assignedtasks->whereHas('worker', function ($query) use ($name) {
                    // Solo números: Buscar por número de documento con el orden exacto
                    $query->where('user_data.document_number', 'LIKE', '%' . $name . '%')
                        ->whereRaw('user_data.document_number REGEXP ?', [preg_quote($name, '/') . '.*']);
                });
            } else {
                // Solo letras: Buscar por nombre
                $assignedtasks->whereHas('worker', function ($query) use ($name) {
                    $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
                });
            }
        }

        // Ejecutar la consulta y devolver los resultados
        $assignedtasks = $assignedtasks->paginate($perPage);

        return response()->json($assignedtasks);
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'estimated_time' => 'nullable|numeric|min:0',
            'type_id' => 'required|exists:task_types,type_id',
            'task_description' => 'required|string|max:255',
            'assigned_to' => 'nullable|exists:user_data,user_data_id',
            'department_id' => 'required|exists:departments,department_id',
            'observations' => 'nullable|string|max:1000',
            'score' => 'nullable|numeric|min:0|max:100',
            'priority' => 'required|numeric|min:0|max:10',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear la tarea
        $task = AssignedTask::create([
            'meeting_id' => $request->meeting_id != '' ? $request->meeting_id : null,
            'start_date' => $request->start_date,
            'estimated_time' => $request->estimated_time != '' ? $request->estimated_time : 0,
            'units' => $request->units  != '' ? $request->units : "Sin definir",
            'type_id' => $request->type_id,
            'task_description' => $request->task_description,
            'assigned_to' => $request->assigned_to != '' ? $request->assigned_to : null,
            'department_id' => $request->department_id,
            'observations' => $request->observations != '' ? $request->observations : null,
            'score' => $request->score != '' ? $request->score : 0,
            "creation_date" => now()->format('Y-m-d H:i:s'),
            'priority' => $request->priority != '' ? $request->priority : 1,
            'created_by' => $request->user_id,
            'reviewed_by' => $request->user_id != '' ? $request->user_id : null,
            'review_date' => $request->reviewed_by != '' ? now()->format('Y-m-d H:i:s') : null,
            'status' => 141,

        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }
}
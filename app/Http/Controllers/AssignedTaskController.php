<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignedTask;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AssignedTaskController extends Controller
{
    public static function list(Request $request) {

        $perPage = $request->get('per_page', 10);
        //with method is used to eager load the relationships :'D
        $assignedtasks = AssignedTask::with(['creation', 'worker', 'department'])->simplePaginate($perPage);

        return response()->json(['assignedtasks' => $assignedtasks]);
    }

    public function search(Request $request)
    {
        $page = $request->get('page');
        

        //obtener parametros de la paginación
        $perPage = $request->get('per_page');
        //obtener parametros de busqueda
        $dateIni = $request->get('dateIni');
        $dateEnd = $request->get('dateEnd');
        $name = $request->get('name');

        $assignedtasks = AssignedTask::query()->where('status', '!=', 28)
                                    ->with(['worker:user_data_id,name,document_number,document_type',
                                            'creation:user_id,username',
                                            'department:department_id,department_name']);


        // Filtrar por fecha de inicio  
        if ($dateIni) { 
            $assignedtasks->whereDate('start_date', '>=', $dateIni); 
        }

        // Filtrar por fecha de fin
        if ($dateEnd) { 
            $assignedtasks->whereDate('start_date', '<=', $dateEnd);
        }

        // Filtrar por nombre de trabajador
        if ($name) {
            $assignedtasks->whereHas('worker', function ($query) use ($name) {
                $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
            });
        }
        
        // Ejecutar la consulta y devolver los resultados
            $assignedtasks = $assignedtasks->paginate($perPage);
    

        return response()->json($assignedtasks);
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'meeting_id' => 'required|exists:meetings,meeting_id',
            'start_date' => 'required|date',
            'estimated_time' => 'required|numeric|min:0',
            'units' => 'required|string|max:50',
            'type_id' => 'required|exists:task_types,type_id',
            'task_description' => 'required|string|max:255',
            'assigned_to' => 'required|exists:user_data,user_data_id',
            'department_id' => 'required|exists:departments,department_id',
            'observations' => 'nullable|string|max:1000',
            'score' => 'nullable|numeric|min:0|max:100',
            'priority' => 'required|numeric|min:1|max:10',
            'created_by' => 'required|exists:user_accounts,user_id',
            'reviewed_by' => 'nullable|exists:user_accounts,user_id',
            'status' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear la tarea
        $task = AssignedTask::create([
            'meeting_id' => $request->meeting_id,
            'start_date' => $request->start_date,
            'estimated_time' => $request->estimated_time,
            'units' => $request->units,
            'type_id' => $request->type_id,
            'task_description' => $request->task_description,
            'assigned_to' => $request->assigned_to,
            'department_id' => $request->department_id,
            'observations' => $request->observations,
            'score' => $request->score,
            "creation_date" => now()->format('Y-m-d H:i:s'),
            'priority' => $request->priority,
            'created_by' => $request->created_by,
            'reviewed_by' => $request->reviewed_by,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    public function view($task_id) {
        $assignedtasks = AssignedTask::where('task_id', $task_id)->first();

        return response()->json(['assignedtaskss' => $assignedtasks]);
    }
}
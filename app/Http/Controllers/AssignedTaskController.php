<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignedTask;
use Illuminate\Support\Facades\Redirect;

class AssignedTaskController extends Controller
{
    public static function list(Request $request) {

        // $dateIni = $request->session()->get('dateIni') ?? '';
        // $dateEnd = $request->session()->get('dateEnd') ?? '';
        // $workerName = $request->session()->get('keyword') ?? '';

        // $assignedtasks = AssignedTask::query();
        //     if ($dateIni) {
        //         $assignedtasks->whereDate('start_date', '>=', $dateIni);
        //     }
        //     if ($dateEnd) {
        //         $assignedtasks->whereDate('start_date', '<=', $dateEnd);
        //     }
        //     if ($workerName) {
        //         $assignedtasks->whereHas('worker', function($query) use ($workerName) {
        //             $query->whereRaw("UPPER(name) LIKE '%".strtoupper($workerName)."%'");
        //         });
        //     }

        //     $assignedtasks = $assignedtasks->orderBy('task_id','desc')->simplePaginate(10);

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

    public function view($task_id) {
        $assignedtasks = AssignedTask::where('task_id', $task_id)->first();

        return response()->json(['assignedtasks' => $assignedtasks]);
    }
}
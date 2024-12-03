<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MeetingController extends Controller
{
    public static function list(Request $request) {

        $perPage = $request->get('per_page', 7);

        $meetings = Meeting::with(['worker','creation','department', 'assistants', 'meeting_topics'])->simplePaginate($perPage);

        return response()->json([
            'meetings' => $meetings
        ]);
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
        $departmentName = $request->get('departmentName');
        $assistantName = $request->get('assistantName');

        $meetings = Meeting::query()->with(['worker:user_data_id,name',
                                            'creation:user_id,username',
                                            'assistants:meeting_id,worker_id',
                                            'assistants.worker:user_data_id,name', // Incluye los datos del worker dentro de assistants
                                            'department:department_id,department_name',
                                            'meeting_topics']);


        // Filtrar por fecha de inicio  
        if ($dateIni) { 
            $meetings->whereDate('meeting_date', '>=', $dateIni); 
        }

        // Filtrar por fecha de fin
        if ($dateEnd) { 
            $meetings->whereDate('meeting_date', '<=', $dateEnd);
        }

        // Filtrar por nombre de trabajador
        if ($name) {
            $meetings->whereHas('worker', function ($query) use ($name) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%']);
            });
        }
        
        //Filtrar por nombre de departamento
        if($departmentName){
            $meetings->whereHas('department', function ($query) use ($departmentName) {
                $query->whereRaw('LOWER(department_name) LIKE ?', ['%' . strtolower($departmentName) . '%']);
            });
        }

        //Filtrar por nombre de asistente
        

        if($assistantName){
            $meetings->whereHas('assistants', function ($query) use ($assistantName) {
                $query->whereHas('worker', function ($query) use ($assistantName) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($assistantName) . '%']);
                });
            });
        }
        
        // Ejecutar la consulta y devolver los resultados
            $meetings = $meetings->paginate($perPage);

        return response()->json($meetings);
    }

    public function view($meeting_id) {
        $meeting = Meeting::where('meeting_id', $meeting_id)->first();

        return response()->json([
            'meeting' => $meeting
        ]);
    }
}
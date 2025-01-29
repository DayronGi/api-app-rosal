<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{

    public static function list(Request $request)
    {
        $perPage = $request->get('per_page', 7);
        $meetings = Meeting::with(['worker', 'creation', 'department', 'assistants', 'meeting_topics'])->orderBy('creation_date', 'desc')->simplePaginate($perPage);

        return response()->json([
            'meetings' => $meetings
        ]);
    }

    public function search(Request $request)
    {
        // Obtener parámetros de la paginación
        $perPage = $request->get('per_page');
        // Obtener parámetros de búsqueda
        $dateIni = $request->get('dateIni');
        $dateEnd = $request->get('dateEnd');
        $description = $request->get('description');
        $assistantName = $request->get('assistantName');
    
        $meetings = Meeting::query()->with([
            'worker:user_data_id,name',
            'creation:user_id,username',
            'assistants:meeting_id,worker_id',
            'assistants.worker:user_data_id,name', // Incluye los datos del worker dentro de assistants
            'department:department_id,department_name',
            'meeting_topics'
        ]);
    
        // Filtrar por fecha de inicio
        if ($dateIni) {
            $meetings->whereDate('meeting_date', '>=', $dateIni);
        }
    
        // Filtrar por fecha de fin
        if ($dateEnd) {
            $meetings->whereDate('meeting_date', '<=', $dateEnd);
        }
    
      // Filtrar por descripción
if ($description) {
    // Buscar la frase exacta en minúsculas
    $meetings->whereRaw('LOWER(meeting_description) LIKE ?', ['%' . strtolower($description) . '%']);
}
            
        // Filtrar por nombre de asistente
        if ($assistantName) {
            // Validar el formato del input
            if (preg_match('/^[a-zA-Z\s]+$/', $assistantName)) {
                // Solo letras: Buscar por nombre
                $meetings->whereHas('assistants', function ($query) use ($assistantName) {
                    $query->whereHas('worker', function ($query) use ($assistantName) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($assistantName) . '%']);
                    });
                });
            } elseif (preg_match('/^\d+$/', $assistantName)) {
                // Solo números: Buscar por número de documento
                $meetings->whereHas('assistants', function ($query) use ($assistantName) {
                    $query->whereHas('worker', function ($query) use ($assistantName) {
                        $query->where('document_number', 'LIKE', '%' . $assistantName . '%')
                            ->whereRaw('document_number REGEXP ?', [preg_quote($assistantName, '/') . '.*']);
                    });
                });
            } else {
                // Solo letras: Buscar por nombre
                $meetings->whereHas('assistants', function ($query) use ($assistantName) {
                    $query->whereHas('worker', function ($query) use ($assistantName) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($assistantName) . '%']);
                    });
                });
            }
        }
    
        // Ejecutar la consulta y devolver los resultados
        $meetings = $meetings->paginate($perPage);
    
        return response()->json($meetings);
    }
    

    public function view($meeting_id)
    {
        $meeting = Meeting::where('meeting_id', $meeting_id)->first();

        return response()->json([
            'meeting' => $meeting
        ]);
    }
}
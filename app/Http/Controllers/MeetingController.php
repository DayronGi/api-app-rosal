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
        //obtener parametros de la paginación
        $perPage = $request->get('per_page');
        //obtener parametros de busqueda
        $dateIni = $request->get('dateIni');
        $dateEnd = $request->get('dateEnd');
        $name = $request->get('name');
        $departmentName = $request->get('departmentName');
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

        // Filtrar por nombre o número de cédula
        if (!empty($request->name)) {
            $name = $request->name;

            // Validar el formato del input
            if (preg_match('/^[a-zA-Z\s]+$/', $name)) {
                // Solo letras: Buscar por nombre
                $meetings->whereHas('worker', function ($query) use ($name) {
                    $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
                });
            } elseif (preg_match('/^\d+$/', $name)) {
                // Solo números: Buscar por número de documento
                $meetings->whereHas('worker', function ($query) use ($name) {
                    // Solo números: Buscar por número de documento con el orden exacto
                    $query->where('user_data.document_number', 'LIKE', '%' . $name . '%')
                        ->whereRaw('user_data.document_number REGEXP ?', [preg_quote($name, '/') . '.*']);
                });
            } else {
                // Solo letras: Buscar por nombre
                $meetings->whereHas('worker', function ($query) use ($name) {
                    $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
                });
            }
        }

        //Filtrar por nombre de departamento
        if ($departmentName) {
            $meetings->whereHas('department', function ($query) use ($departmentName) {
                $query->whereRaw('LOWER(department_name) LIKE ?', ['%' . strtolower($departmentName) . '%']);
            });
        }

        //Filtrar por nombre de asistente
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
                        // Buscar que el document_number contenga la cadena de números en cualquier parte
                        $query->where('document_number', 'LIKE', '%' . $assistantName . '%')
                            // Usar REGEXP para asegurar que el número de documento contiene el número en el orden correcto
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\License;
use App\Models\Worker;

class LicenseController extends Controller
{
    public function search(Request $request)
    {
        //obtener parametros de la paginación
        $perPage = $request->get('per_page');
        //obtener parametros de busqueda
        $dateIni = $request->get('dateIni');
        $dateEnd = $request->get('dateEnd');
        $name = $request->get('name');

        $licenses = License::query()->whereIn('status', [29, 30])->with([
                'worker:user_data_id,name,document_number,document_type',
                'creation:user_id,username'
            ])->orderBy('creation_date', 'desc');
        // Filtrar por fecha de inicio
        if ($dateIni) {
            $licenses->whereDate('creation_date', '>=', $dateIni);
        }

        // Filtrar por fecha de fin
        if ($dateEnd) {
            $licenses->whereDate('creation_date', '<=', $dateEnd);
        }

        // Filtrar por nombre o número de cédula
        if (!empty($request->name)) {
            $name = $request->name;

            // Validar el formato del input
            if (preg_match('/^[a-zA-Z\s]+$/', $name)) {
                // Solo letras: Buscar por nombre
                $licenses->whereHas('worker', function ($query) use ($name) {
                    $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
                });
            } elseif (preg_match('/^\d+$/', $name)) {
                // Solo números: Buscar por número de documento
                $licenses->whereHas('worker', function ($query) use ($name) {
                    // Solo números: Buscar por número de documento con el orden exacto
                    $query->where('user_data.document_number', 'LIKE', '%' . $name . '%')
                        ->whereRaw('user_data.document_number REGEXP ?', [preg_quote($name, '/') . '.*']);
                });
            } else {
                // Solo letras: Buscar por nombre
                $licenses->whereHas('worker', function ($query) use ($name) {
                    $query->whereRaw('LOWER(user_data.name) LIKE ?', ['%' . strtolower($name) . '%']);
                });
            }
        }
        // Ejecutar la consulta y devolver los resultados
        $licenses = $licenses->paginate($perPage);

        $motives = [
            'Llega tarde',
            'Asuntos médicos',
            'Asuntos personales',
            'Inasistencia no justificada',
            'Tiempo compensado',
            'Trámites empresa',
            'Trámites personales',
            'Otros'
        ];

        return response()->json([
            'license' => $licenses,
            'motives' => $motives,
        ]);
    }

    public function create()
    {
        $workers = Worker::all();
        $motives = [
            'Llega tarde',
            'Asuntos médicos',
            'Asuntos personales',
            'Inasistencia no justificada',
            'Tiempo compensado',
            'Trámites empresa',
            'Trámites personales',
            'Otros'
        ];

        return response()->json([
            'workers' => $workers,
            'motives' => $motives
        ]);
    }

    public function store(Request $request)
    {
        $license = new License();

        $license->spreadsheet_id = $request->spreadsheet_id ?? '';
        $license->worker_id = $request->worker_id;
        $license->start_date = $request->start_hour != '' ? $request->start_date . " " . $request->start_hour : '';
        $license->end_date = $request->end_hour != '' ? $request->start_date . " " . $request->end_hour : null;
        $license->type = $request->type != "0" ? "Permiso pagado" : "Permiso";
        $license->motive = $request->motive;
        $license->internal_reference = "";
        $license->observations = $request->observations != '' ? $request->observations : '';
        $license->created_by = $request->user_id;
        $license->creation_date = now()->format('Y-m-d H:i:s');
        $license->status = 29;

        $license->save();

        return response()->json(['license' => $license]);
    }

    public function edit($license_id)
    {
        $motives = [
            'Llega tarde',
            'Asuntos médicos',
            'Asuntos personales',
            'Inasistencia no justificada',
            'Tiempo compensado',
            'Trámites empresa',
            'Trámites personales',
            'Otros'
        ];

        $license = License::where('license_id', $license_id)->first();

        return response()->json([
            'license' => $license,
            'motives' => $motives,
        ]);
    }

    public function update(Request $request)
    {
        $license = License::where('license_id', $request->license_id)->first();

        $license->spreadsheet_id = $request->spreadsheet_id != null ? $request->spreadsheet_id : '';
        $license->worker_id = $request->worker_id;
        $license->start_date = $request->start_date . " " . $request->start_hour;
        $license->end_date = $request->end_hour != '' ? $request->start_date . " " . $request->end_hour : null;
        $license->motive = $request->motive;
        $license->internal_reference = $request->internal_reference;
        $license->type = $request->type != "0" ? "Permiso pagado" : "Permiso";
        $license->observations = $request->observations;
        $license->status = $request->status;
        $license->updated_by = $request->user_id;
        $license->update_date = now()->format('Y-m-d H:i:s');

        $license->save();

        return response()->json(['license' => $license]);
    }
}
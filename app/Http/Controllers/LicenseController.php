<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\License;
use App\Models\Worker;
use Illuminate\Support\Facades\Redirect;

class LicenseController extends Controller
{
    public static function list(Request $request) {

        $licenses = License::where('status', '!=', 28)->with(['worker:user_data_id,name,document_number,document_type', 'creation:user_id,username']);

        $licenses = $licenses->orderBy('license_id','desc')->simplePaginate(8);

        return response()->json(['licenses' => $licenses]);
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

        $licenses = License::query()->where('status', '!=', 28)
                                    ->with(['worker:user_data_id,name,document_number,document_type',
                                            'creation:user_id,username']);


        // Filtrar por fecha de inicio  
        if ($dateIni) { 
            $licenses->whereDate('creation_date', '>=', $dateIni); 
        }

        // Filtrar por fecha de fin
        if ($dateEnd) { 
            $licenses->whereDate('creation_date', '<=', $dateEnd);
        }

        // Filtrar por nombre de trabajador
        if ($name) {
            $licenses->whereHas('worker', function ($query) use ($name) {
                $query->whereRaw('LOWER(user_data.name) LIKE?', ['%' . strtolower($name) . '%']);
            });
        }
        
        // Ejecutar la consulta y devolver los resultados
            $licenses = $licenses->paginate($perPage);
    

        return response()->json($licenses);
    }

    public function create() {
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
            'workers'=>$workers,
            'motives'=>$motives
        ]);
    }

    public function store(Request $request) {
        $license = new License();

        $license->spreadsheet_id = $request->spreadsheet_id ?? '';
        $license->worker_id = $request->worker_id;
        $license->start_date = $request->end_hour != '' ?$request->start_date." " .$request->start_hour: '';
        $license->end_date = $request->end_hour != '' ? $request->start_date. " " .$request->end_hour : '';
        $license->type = "Permiso";
        $license->motive = $request->motive;
        $license->internal_reference = "";
        $license->observations = $request->observations;
        $license->created_by = 1;
        $license->creation_date = now()->format('Y-m-d H:i:s');
        $license->status = 29;

        $license->save();

        return response()->json(['license' => $license]);
    }

    public function edit($license_id) {

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
            'license'=>$license,
            'motives'=>$motives,
        ]);
    }

    public function update(int $license_id, Request $request) {

        $license = License::where('license_id', $license_id)->first();

        $license->spreadsheet_id = $request->spreadsheet_id;
        $license->worker_id = $request->worker_id;
        $license->start_date = $request->start_date." " .$request->start_hour;
        $license->end_date = $request->end_hour != '' ? $request->start_date. " " .$request->end_hour : '';
        $license->motive = $request->motive;
        $license->internal_reference = $request->internal_reference;
        $license->observations = $request->observations;
        $license->status = $request->status ?? $license->status;
        $license->updated_by = 1;
        $license->update_date = now()->format('Y-m-d H:i:s');

        $license->save();

        return response()->json(['license' => $license]);
    }
}
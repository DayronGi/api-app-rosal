<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;

class WorkerController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->get('name');
        $perPage = 40;

        $worker = Worker::query()->where('status', '!=', 1);
        // Filtrar por nombre de trabajador
        if ($name) {
            // Validar el formato del input
            if (preg_match('/^[a-zA-Z\s]+$/', $name)) {
                // Solo letras: Buscar por nombre
                $worker->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%']);
            } elseif (preg_match('/^\d+$/', $name)) {
                // Solo números: Buscar por número de documento
                $worker->where('document_number', 'LIKE', '%' . $name . '%')
                    ->whereRaw('document_number REGEXP ?', [preg_quote($name, '/') . '.*']);
            } else {
                $worker->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%']);
            }

            $worker = $worker->paginate($perPage);

            return response()->json($worker);
        }
    }

    public function store(Request $request)
    {
        $worker = new Worker();
        $worker->name = $request->name;
        $worker->document_type = $request->document_type;
        $worker->document_number = $request->document_number;
        $worker->status = $request->status;
        $worker->altername = $request->altername;
        $worker->surname = $request->surname;
        $worker->photo = $request->photo;
        $worker->profile = $request->profile;
        $worker->address = $request->address;
        $worker->city = $request->city;
        $worker->phone = $request->phone;
        $worker->email = $request->email;
        $worker->hiring_date = $request->hiring_date;
        $worker->birthdate = $request->birthdate;
        $worker->user_type = $request->exemployee;
        $worker->is_employee = $request->is_employee;
        $worker->is_client = $request->is_client;
        $worker->is_provider = $request->is_provider;
        $worker->business_id = $request->business_id;
        $worker->driver_license = $request->driver_license;
        $worker->marita_status  = $request->marita_status;
        $worker->fund = $request->fund;
        $worker->create_by = $request->create_by;
        $worker->status = $request->status;
        $worker->save();
        return response()->json($worker);
    }
}
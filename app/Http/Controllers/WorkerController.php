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

        $worker = Worker::query()->where('status', 2)->where('is_employee', 1);
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
}
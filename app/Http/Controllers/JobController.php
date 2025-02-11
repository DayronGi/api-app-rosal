<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->get('name');
        $perPage = 200;
        $job = Job::query()->where('status', 2)->orderBy('status', 'DESC')->orderBy('internal_code', 'ASC');
        // Filtrar por nombre del trabajo, codigo interno o referencia interna
        if ($name) {
            $job->where(function ($query) use ($name) {
                $query->whereRaw('LOWER(job_description) LIKE ?', ['%' . strtolower($name) . '%'])
                    ->orWhereRaw('LOWER(internal_code) LIKE ?', ['%' . strtolower($name) . '%']);
            });
        }
        // Ejecutar la consulta y devolver los resultados
        $job = $job->paginate($perPage);

        return response()->json($job);
    }
}
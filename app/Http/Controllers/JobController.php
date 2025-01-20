<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->get('name');
        $perPage = 40;
        $job = Job::query()->where('status', '!=', 1);
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

    public function store(Request $request)
    {
        $job = new Job();
        $job->job_description = $request->job_description;
        $job->internal_code = $request->internal_code;
        $job->status = $request->status;
        $job->save();
        return response()->json($job);
    }
}

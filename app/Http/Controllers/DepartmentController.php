<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function search(Request $request)
    {
        $department_name = $request->get('departmentName');
        $perPage = 10;
        $department = Department::query()->where('status', '!=', 1);

        // Filtrar por nombre del departamento
        if ($department_name) {
            $department->whereRaw('LOWER(department_name) LIKE ? ', ['%' . strtolower($department_name) . '%']);
        }
        // Ejecutar la consulta y devolver los resultados
        $department = $department->paginate($perPage);

        return response()->json($department);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->get('name');
        $perPage = 40;
        $product = DB::connection('pgsql')->table('manager_rosal.producto')
            ->selectRaw("
                TRIM(procodigo) AS product_id, TRIM(pronombre) AS product_description, TRIM(tllnombre) AS packing,
                TRIM(proubicaci) AS section, 0 AS price,
                TRIM(procodigo) AS plant_id,
                CONCAT(TRIM(procodigo), ' [', TRIM(prolinea), '] - ', TRIM(procorto)) AS common_name,
                '' AS substratum, '' AS irrigation, '' AS sunlight,
                proactivo AS status, '' AS icon, '' AS class, 'Activo' AS title
            ")
            ->leftJoin('manager_rosal.talla', 'tllcodigo', '=', 'protalla')
            ->whereIn('prolinea', ['20', '26', '33', '40'])
            ->where('proactivo', '>', 0)
            ->orderBy('packing');

        // Filtrar por nombre del trabajo, codigo interno o referencia interna
        if ($name) {
            $product->where(function ($query) use ($name) {
                $query->whereRaw('LOWER(procorto) LIKE ?', ['%' . strtolower($name) . '%'])
                    ->orWhereRaw('LOWER(tllnombre) LIKE ?', ['%' . strtolower($name) . '%'])
                    ->orWhereRaw('LOWER(procodigo) LIKE ?', ['%' . strtolower($name). '%' ]);
            });
        }

        // Ejecutar la consulta y devolver los resultados
        $product = $product->paginate($perPage);

        return response()->json($product);
    }
}
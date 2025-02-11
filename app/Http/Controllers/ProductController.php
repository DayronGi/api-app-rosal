<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $name = $request->get('name');
        $perPage = 40;
        $product = Product::select('product_id', 'product_description', 'packing', 'section')
            ->selectRaw("CONCAT(TRIM(product_id), ' [', TRIM(line_id), '] - ', TRIM(shortname)) AS common_name")
            ->whereIn('line_id', ['20', '26', '33', '40'])
            ->where('status', 2)
            ->orderBy('shortname')
            ->orderBy('packing', 'DESC');

        // Filtrar por nombre del trabajo, codigo interno o referencia interna
        if ($name) {
            $product->where(function ($query) use ($name) {
                $query->whereRaw('LOWER(shortname) LIKE ?', ['%' . strtolower($name) . '%'])
                    ->orWhereRaw('LOWER(packing) LIKE ?', ['%' . strtolower($name) . '%'])
                    ->orWhereRaw('LOWER(product_id) LIKE ?', ['%' . strtolower($name). '%' ]);
            });
        }

        // Ejecutar la consulta y devolver los resultados
        $product = $product->paginate($perPage);

        return response()->json($product);
    }
}
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
        $product = Product::query()->where('status', '!=', 1);


        // Filtrar por nombre del trabajo, codigo interno o referencia interna
        if ($name) {
            $product->where(function ($query) use ($name) {
                $query->whereRaw('LOWER(shortname) LIKE ?', ['%' . strtolower($name) . '%'])
                    ->orWhereRaw('LOWER(packing) LIKE ?', ['%' . strtolower($name) . '%']);
            });
        }

        // Ejecutar la consulta y devolver los resultados

        $product = $product->paginate($perPage);

        return response()->json($product);
    }
}
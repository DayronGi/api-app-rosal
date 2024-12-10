<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
            $name = $request->get('name');
            $page = 1;
            $perPage = 40;
            $product = Product::query()->where('status', '!=', 1)
                                     ;  
    
    
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
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSection;
use Illuminate\Support\Facades\Validator;

class ProductSectionController extends Controller
{
    public function list() {
        $productSection = ProductSection::query()->with([
            'product_id:product_id,product_description',
            'created_by:user_id,username'
        ])->orderBy('creation_date', 'desc')->get();
        return response()->json(['productSection' => $productSection]);
    }
    
    public function store(Request $request) {   
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|string',
            'section' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'user_id' => 'required|integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $productSection = new ProductSection();
        $productSection->product_id = $request->product_id;
        $productSection->section = $request->section;
        $productSection->quantity = $request->quantity;
        $productSection->created_by = $request->user_id;
        $productSection->creation_date = now();
        $productSection->status = 2;
        $productSection->save();
        return response()->json(['productSection' => $productSection]);
    }

    public function update(Request $request, $product_id, $section) {
        $productSection = ProductSection::where([
            ['product_id', '=', $product_id],
            ['section', '=', $section]
        ])->first();

        $productSection->quantity = $request->quantity;
        $productSection->save();
        return response()->json(['productSection' => $productSection]);
    }

    public function delete ($product_id, $section) {
        $productSection = ProductSection::where([
            ['product_id', '=', $product_id],
            ['section', '=', $section]
        ])->first();
        
        $productSection->status = 1;
        $productSection->save();
        return response()->json(['productSection' => $productSection]);
    }
}
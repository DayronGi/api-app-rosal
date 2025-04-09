<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductMovement;
use Illuminate\Support\Facades\Validator;

class ProductMovementController extends Controller
{
    public function list() {
        $productMovement = ProductMovement::query()->with([
            'updated_by:user_id,username',
            'created_by:user_id,username',
            'product_id:product_id,product_description'
        ])->orderBy('creation_date', 'desc')->get();
        return response()->json(['productMovement' => $productMovement]);
    }

    public function get($movement_id) {
        $productMovement = ProductMovement::find($movement_id) ->with([
            'updated_by:user_id,username',
            'created_by:user_id,username',
            'product_id:product_id,product_description'
        ])->orderBy('creation_date', 'desc')->get();
        return response()->json(['productMovement' => $productMovement]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'movement_type' => 'required|integer',
            'movement_date' => 'required|date',
            'product_id' => 'required|string',
            'section' => 'required|string|',
            'income' => 'nullable|numeric|min:0',
            'outcome' => 'nullable|numeric|min:0',
            'details' => 'nullable|string|max:255',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $productMovement = new ProductMovement();
        $productMovement->movement_type = $request->movement_type;
        $productMovement->movement_date = $request->movement_date ? $request->movement_date : now();
        $productMovement->product_id = $request->product_id;
        $productMovement->section = $request->section;
        $productMovement->income = $request->income ? $request->income : 0;
        $productMovement->outcome = $request->outcome ? $request->outcome : 0;
        $productMovement->details = $request->details;
        $productMovement->created_by = $request->user_id;
        $productMovement->creation_date = now();
        $productMovement->status = 2;
        $productMovement->save();
        return response()->json(['productMovement' => $productMovement]);
    }

    public function approve(Request $request, $movement_id) {
        $productMovement = ProductMovement::find($movement_id);
        $productMovement->income = $request->income ? $request->income : 0;
        $productMovement->outcome = $request->outcome ? $request->outcome : 0;
        $productMovement->updated_by = $request->user_id;
        $productMovement->update_date = now();
        $productMovement->status = 3;
        $productMovement->save();
        return response()->json(['productMovement' => $productMovement]);
    }

    public function delete($movement_id) {
        $productMovement = ProductMovement::find($movement_id);
        $productMovement->status = 1;
        $productMovement->save();
        return response()->json(['productMovement' => $productMovement]);
    }
}
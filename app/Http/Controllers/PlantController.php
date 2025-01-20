<?php

namespace App\Http\Controllers;

use App\Models\Plant;

class PlantController extends Controller
{
    public static function get_plants() {
        return Plant::all();
    }
}

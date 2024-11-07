<?php

namespace App\Http\Controllers;

use App\Models\Job;

class JobController extends Controller
{
    public static function get_jobs() {

        $jobs = Job::all();

        return response()->json(['jobs' => $jobs]);
    }
}

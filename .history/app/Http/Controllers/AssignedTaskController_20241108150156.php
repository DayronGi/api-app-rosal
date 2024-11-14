<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignedTask;
use Illuminate\Support\Facades\Redirect;

class AssignedTaskController extends Controller
{
    public static function list(Request $request) {

        // $dateIni = $request->session()->get('dateIni') ?? '';
        // $dateEnd = $request->session()->get('dateEnd') ?? '';
        // $workerName = $request->session()->get('keyword') ?? '';

        // $assignedtasks = AssignedTask::query();
        //     if ($dateIni) {
        //         $assignedtasks->whereDate('start_date', '>=', $dateIni);
        //     }
        //     if ($dateEnd) {
        //         $assignedtasks->whereDate('start_date', '<=', $dateEnd);
        //     }
        //     if ($workerName) {
        //         $assignedtasks->whereHas('worker', function($query) use ($workerName) {
        //             $query->whereRaw("UPPER(name) LIKE '%".strtoupper($workerName)."%'");
        //         });
        //     }

        //     $assignedtasks = $assignedtasks->orderBy('task_id','desc')->simplePaginate(10);

        $perPage = $request->get('per_page', 10);
        //with method is used to eager load the relationships :'D
        $assignedtasks = AssignedTask::with(['creation', 'worker', 'department','product'])->simplePaginate($perPage);

        return response()->json(['assignedtasks' => $assignedtasks]);
    }

    public function search(Request $request) {
        if ($request->get('action')=='CLEAR') {
            $request->session()->put('dateIni', '');
            $request->session()->put('dateEnd', '');
            $request->session()->put('keyword', '');
        }
        if ($request->get('action')=='SEARCH') {
            $request->session()->put('dateIni', $request->get('date_ini'));
            $request->session()->put('dateEnd', $request->get('date_end'));
            $request->session()->put('keyword', $request->get('keyword'));
        }
        return Redirect::to('/assignedtasks');
    }

    public function view($task_id) {
        $assignedtasks = AssignedTask::where('task_id', $task_id)->first();

        return response()->json(['assignedtasks' => $assignedtasks]);
    }
}
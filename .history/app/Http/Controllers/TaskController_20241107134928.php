<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Product;
use App\Models\Task;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TaskController extends Controller
{
    public function list(Request $request) {

        // $dateIni = $request->session()->get('dateIni') ?? '';
        // $dateEnd = $request->session()->get('dateEnd') ?? '';
        // $workerName = $request->session()->get('keyword') ?? '';

        // $tasks = Task::query();
        // if ($dateIni) {
        //     $tasks->whereDate('day', '>=', $dateIni);
        // }
        // if ($dateEnd) {
        //     $tasks->whereDate('day', '<=', $dateEnd);
        // }
        // if ($workerName) {
        //     $tasks->whereHas('worker', function($query) use ($workerName) {
        //         $query->whereRaw("UPPER(name) LIKE '%".strtoupper($workerName)."%'");
        //     });
        // }
        // $tasks = $tasks->orderBy('task_id','desc')->simplePaginate(7);

        // foreach ($tasks as $task) {
        //     $task->total = $task->cantidad_unidades * $task->precio_unidad;
        // }
        $perPage = $request->get('per_page', 7);

        $tasks = Task::simplePaginate($perPage);

        return response()->json([
            'tasks' => $tasks
        ]);
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
        return Redirect::to('/tasks');
    }

    public function create() {
        $workers = Worker::select('user_data_id', 'name', 'document_type', 'document_number')->get();
        $jobs = Job::select('job_id', 'internal_code', 'job_description', 'price')->get();
        $plants =  Product::select('product_id', 'packing')->get();

        return response()->json([
            'workers'=>$workers,
            'jobs'=>$jobs,
            'plants'=>$plants
        ]);
    }

    public function store(Request $request) {
        $task = new Task();

        $task->day = $request->day;
        $task->worker_id = $request->worker_id;
        $task->job_id = $request->job_id;
        $task->plant_id = $request->plant_id;
        $task->plant_from_id = $request->plant_from_id;
        $task->seccion = $request->seccion;
        $task->seccion_origen = $request->seccion_origen;
        $task->mesa = $request->mesa;
        $task->cantidad_ingresada = $request->cantidad_ingresada;
        $task->cantidad_unidades = eval("return ".$request->cantidad_ingresada.";");
        $task->cantidad_usada = eval("return ".$request->cantidad_usada.";");
        $task->precio_unidad = $request->precio_unidad;
        $task->observations = $request->observations;
        $task->calification = $request->calification;
        $task->created_by = auth()->user()->user_id;
        $task->creation_date = now();
        $task->lote = 1;
        $task->status = 5;

        $task->save();

        return response()->json(['message' => 'Task created successfully']);
    }
}
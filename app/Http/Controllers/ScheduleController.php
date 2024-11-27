<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduledWorker;
use App\Models\ScheduleReview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function list()
    {
       
        $schedules = Schedule::with(['department', 'scheduledWorkers.worker','scheduledWorkers'=> function($query) {
            $query->select('scheduled_workers_id', 'schedule_id', 'worker_id', 'day', 'hour_ini1', 'hour_end1', 'hour_ini2', 'hour_end2','normal', 'extra');
        }])->get();

        return response()->json(['schedules' => $schedules]);
    }

    public function review($schedule_id)
    {
        $schedule = Schedule::where('schedule_id', $schedule_id)->first();
        $scheduled_workers = ScheduledWorker::where('schedule_id', $schedule_id)->get();
        $current_date = Carbon::now()->format('Y-m-d');
        $schedule_days = $this->get_schedule_days($schedule_id);

        foreach ($scheduled_workers as $worker) {
            $worker->program = $worker->normal + $worker->extra;
        }

        return response()->json([
            'schedule' => $schedule,
            'scheduled_workers' => $scheduled_workers,
            'current_date' => $current_date,
            'schedule_days' => $schedule_days
        ]);
    }

    public function store_reviewed(Request $request)
    {
        $validator = validator::make($request->all(), [
            'worker_id' => 'required|array',
            'worker_id.*' => 'required|integer',
            'day' => 'required|array',
            'day.*' => 'required|string',
            'hour_ini1' => 'required|array',
            'hour_ini1.*' => 'required|string',
            'hour_end1' => 'required|array',
            'hour_end1.*' => 'required|string',
            'hour_ini2' => 'nullable|array',
            'hour_ini2.*' => 'nullable|string',
            'hour_end2' => 'nullable|array',
            'hour_end2.*' => 'nullable|string',
            'normal' => 'required|array',
            'normal.*' => 'required|numeric',
            'extra' => 'required|array',
            'extra.*' => 'required|numeric',
            'review_date' => 'required|date',
            'type' => 'required|array',
            'type.*' => 'required|string',
        ]);
        $scheduleData = [];

        foreach ($request->worker_id as $index => $workerId) {
            $scheduleData[] = [
                'worker_id' => $workerId,
                'day' => $request->day[$index],
                'hour_ini1' => $request->hour_ini1[$index],
                'hour_end1' => $request->hour_end1[$index],
                'hour_ini2' => isset($request->hour_ini2[$index]) && $request->hour_ini2[$index] !== '' ? $request->hour_ini2[$index] : null,
                'hour_end2' => isset($request->hour_end2[$index]) && $request->hour_end2[$index] !== '' ? $request->hour_end2[$index] : null,
                'normal' => $request->normal[$index],
                'extra' => $request->extra[$index],
                'review_date' => $request->review_date,
                'type' => $request->type[$index],
                'reviewed_by' => isset($request->reviewed_by) && $request->reviewed_by !== '' ? $request->reviewed_by : null,
                'status' => isset($request->status[$index]) && $request->status[$index] !== '' ? $request->status[$index] : 210,
            ];
        }

        ScheduleReview::insert($scheduleData);

        return response()->json(['message' => 'Schedule created successfully']);
    }


    public static function get_schedule_days($schedule_id)
    {
        return DB::select(
            "
                SELECT scheduled_workers.day
                FROM scheduled_workers
                WHERE scheduled_workers.schedule_id = ?
                GROUP BY scheduled_workers.day
            ",
            [$schedule_id]
        );
    }
}

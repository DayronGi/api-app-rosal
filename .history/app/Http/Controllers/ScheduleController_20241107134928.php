<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\ScheduledWorker;
use App\Models\ScheduleReview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function list()
    {
        $schedules = Schedule::with(['scheduledWorkers' => function($query) {
            $query->select('scheduled_workers_id', 'schedule_id', 'worker_id', 'day', 'hour_ini1', 'hour_end1', 'hour_ini2', 'hour_end2');
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

    public function store(Request $request)
    {
        $scheduleData = [];

        foreach ($request->worker_id as $index => $workerId) {
            $scheduleData[] = [
                'worker_id' => $workerId,
                'day' => $request->day[$index],
                'hour_ini1' => $request->hour_ini1[$index],
                'hour_end1' => $request->hour_end1[$index],
                'hour_ini2' => $request->hour_ini2[$index],
                'hour_end2' => $request->hour_end2[$index],
                'normal' => $request->normal[$index],
                'extra' => $request->extra[$index],
                'review_date' => $request->review_date,
                'type' => $request->type[$index],
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
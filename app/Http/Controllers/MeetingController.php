<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MeetingController extends Controller
{
    public static function list(Request $request) {

        // $dateIni = $request->session()->get('dateIni') ?? '';
        // $dateEnd = $request->session()->get('dateEnd') ?? '';
        // $workerName = $request->session()->get('keyword') ?? '';

        // $meetings = Meeting::query();
        //     if ($dateIni) {
        //         $meetings->whereDate('meeting_date', '>=', $dateIni);
        //     }
        //     if ($dateEnd) {
        //         $meetings->whereDate('meeting_date', '<=', $dateEnd);
        //     }
        //     if ($workerName) {
        //         $meetings->whereHas('worker', function($query) use ($workerName) {
        //             $query->whereRaw("UPPER(name) LIKE '%".strtoupper($workerName)."%'");
        //         });
        //     }

        //     $meetings = $meetings->orderBy('meeting_id','desc')->simplePaginate(7);

        $perPage = $request->get('per_page', 7);

        $meetings = Meeting::simplePaginate($perPage);

        return response()->json([
            'meetings' => $meetings
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
        return Redirect::to('/meetings');
    }

    public function view($meeting_id) {
        $meeting = Meeting::where('meeting_id', $meeting_id)->first();

        return response()->json([
            'meeting' => $meeting
        ]);
    }
}
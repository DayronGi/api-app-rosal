<?php

namespace App\Http\Controllers;

use App\Models\Handbook;
use App\Models\HandbookSection;
use Illuminate\Http\Request;

class HandbookController extends Controller
{
    public static function list(Request $request)
    {
        // $handbookTitle = $request->session()->get('handbookTitle') ?? '';
        // $positionName = $request->session()->get('positionName') ?? '';

        // $handbooks = Handbook::query();
        // if ($handbookTitle) {
        //     $handbooks->whereHas('handbook_title', function ($query) use ($handbookTitle) {
        //         $query->whereRaw("UPPER(name) LIKE '%" . strtoupper($handbookTitle) . "%'");
        //     });
        // }

        // if ($positionName) {
        //     $handbooks->whereHas('position_name', function ($query) use ($positionName) {
        //         $query->whereRaw("UPPER(name) LIKE '%" . strtoupper($positionName) . "%'");
        //     });
        // }

        // $handbooks = $handbooks->orderBy('handbook_id', 'desc')->simplePaginate(8);

        $perPage = $request->input('per_page', 8);

        $handbooks = Handbook::orderBy('handbook_id', 'desc')->with('creation:user_id,username','department:department_id,department_name','update:update_by,username')->simplePaginate($perPage);

        return response()->json(['handbooks' => $handbooks]);
    }

    public function view($handbook_id)
    {
        $handbook_sections = HandbookSection::where([
            ['handbook_id', $handbook_id],
            ['status', 113]
        ])->get();

        $chapters = array();

        if ($handbook_sections != null) {
            foreach ($handbook_sections as $row) {
                if ($row['section_type']=='chapter') {
                    $chapters[$row['hb_section_id']]['hb_section_id'] = $row['hb_section_id'];
                    $chapters[$row['hb_section_id']]['handbook_id'] = $row['handbook_id'];
                    $chapters[$row['hb_section_id']]['section_type'] = $row['section_type'];
                    $chapters[$row['hb_section_id']]['section_index'] = $row['section_index'];
                    $chapters[$row['hb_section_id']]['section_title'] = $row['section_title'];
                    $chapters[$row['hb_section_id']]['section_content'] = $row['section_content'];
                    $chapters[$row['hb_section_id']]['status'] = $row['status'];
                }

                if ($row['section_type']=='section') {
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']]['hb_section_id'] = $row['hb_section_id'];
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']]['handbook_id'] = $row['handbook_id'];
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']]['chapter_id'] = $row['chapter_id'];
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']]['section_type'] = $row['section_type'];
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']]['section_index'] = $row['section_index'];
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']]['section_title'] = $row['section_title'];
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']]['section_content'] = $row['section_content'];
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']]['status'] = $row['status'];
                }
                if ($row['section_type']=='subsection') {
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['hb_section_id'] = $row['hb_section_id'];
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['handbook_id'] = $row['handbook_id'];
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['chapter_id'] = $row['chapter_id'];
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['section_id'] = $row['section_id'];
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['section_type'] = $row['section_type'];
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['section_index'] = $row['section_index'];
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['section_title'] = $row['section_title'];
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['section_content'] = $row['section_content'];
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']]['status'] = $row['status'];
                }
            }
        }

        return response()->json([
            'handbook_sections' => $handbook_sections,
            'chapters' => $chapters
        ]);
    }
}
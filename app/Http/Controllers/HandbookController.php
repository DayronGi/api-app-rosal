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

        $handbooks = Handbook::orderBy('handbook_id', 'desc')->simplePaginate($perPage);

        return response()->json(['handbooks' => $handbooks]);
    }

    public function view($handbook_id)
    {
        $handbook_sections = HandbookSection::where([
            ['handbook_id', $handbook_id],
            ['status', 113]
        ])->get();

        $chapters = array();
        $base_url = '../../../../sgrosal/Views/handbooks/handbooks';

        if ($handbook_sections != null) {
            foreach ($handbook_sections as $row) {
                $row['section_content'] = $this->updateImageUrls($row['section_content'], $base_url);
                if ($row['section_type'] == 'chapter') {
                    $chapters[$row['hb_section_id']] = $this->buildSectionArray($row, $base_url);
                }

                if ($row['section_type'] == 'section') {
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']] = $this->buildSectionArray($row, $base_url);
                }

                if ($row['section_type'] == 'subsection') {
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']] = $this->buildSectionArray($row, $base_url);
                }
            }
        }

        return response()->json([
            'handbook_sections' => $handbook_sections,
            'chapters' => $chapters
        ]);
    }

    private function buildSectionArray($row, $base_url)
    {
        return [
            'hb_section_id' => $row['hb_section_id'],
            'handbook_id' => $row['handbook_id'],
            'section_type' => $row['section_type'],
            'section_index' => $row['section_index'],
            'section_title' => $row['section_title'],
            'section_content' => $row['section_content'],
            'status' => $row['status'],
            'url' => $base_url . 'handbooks/' . $row['hb_section_id'] . '.html'
        ];
    }

    private function updateImageUrls($content, $base_url)
    {
        return preg_replace('/src=["\'](?!http)([^"\']+)["\']/', 'src="' . $base_url . '$1"', $content);
    }
}
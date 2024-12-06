<?php

namespace App\Http\Controllers;

use App\Models\Handbook;
use App\Models\HandbookSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HandbookController extends Controller
{
    public static function list(Request $request)
    {

        $perPage = $request->input('per_page', 8);

        $handbooks = Handbook::orderBy('handbook_id', 'desc')->with('creation:user_id,username','department:department_id,department_name','updateByUser:user_id,username')->simplePaginate($perPage);

        return response()->json(['handbooks' => $handbooks]);
    }

    public function search(Request $request)
    {
        //obtener parametros de la paginación
        $perPage = $request->get('per_page');
        //obtener parametros de busqueda
        $departmentName = $request->get('departmentName');
        $positionName = $request->get('positionName');
        $handbookTitle = $request->get('handbookTitle');

        $handbooks = Handbook::query()->with(   'creation:user_id,username',
                                            'department:department_id,department_name',
                                            'updateByUser:user_id,username');

        // Filtrar por fecha de inicio  
        if ($departmentName) {
            $handbooks->whereHas('department', function ($query) use ($departmentName) {
                $query->whereRaw('LOWER(department_name) LIKE ?', ['%' . strtolower($departmentName) . '%']);
            });
        }
    
        // Filtrar por nombre de posición
        if ($positionName) {
            $handbooks->whereRaw('LOWER(position_name) LIKE ? ', ['%' . strtolower($positionName) . '%']);

        }

        // Filtrar por nombre de trabajador
        if ($handbookTitle) {
            $handbooks->whereRaw('LOWER(handbook_title) LIKE ? ', ['%' . strtolower($handbookTitle) . '%']);
        
        }

        // Ejecutar la consulta y devolver los resultados
            $handbooks = $handbooks->paginate($perPage);

        return response()->json($handbooks);
    }

    public function view($handbook_id)
    {
        $handbook_sections = HandbookSection::where([
            ['handbook_id', $handbook_id],
            ['status', 113]
        ])->get();

        $chapters = array();
        $base_url = 'http://192.168.2.7:81/sgrosal/Views/handbooks/';

        if ($handbook_sections != null) {
            foreach ($handbook_sections as $row) {
                $row['section_content'] = $this->updateImageUrls($row['section_content'], $base_url);
                if ($row['section_type'] == 'chapter') {
                    $chapters[$row['hb_section_id']] = $this->buildSectionArray($row, $base_url);
                $row['section_content'] = $this->updateImageUrls($row['section_content'], $base_url);
                if ($row['section_type'] == 'chapter') {
                    $chapters[$row['hb_section_id']] = $this->buildSectionArray($row, $base_url);
                }

                if ($row['section_type'] == 'section') {
                    $chapters[$row['chapter_id']]['sections'][$row['hb_section_id']] = $this->buildSectionArray($row, $base_url);
                }

                if ($row['section_type'] == 'subsection') {
                    $chapters[$row['chapter_id']]['sections'][$row['section_id']]['subsections'][$row['hb_section_id']] = $this->buildSectionArray($row, $base_url);

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
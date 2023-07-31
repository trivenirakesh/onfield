<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SkillCreateUpdateRequest;
use App\Models\Skill;
use App\Services\V1\SkillService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SkillController extends Controller
{
    use CommonTrait;
    protected $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.skill.index');
            $data = Skill::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, false);
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'name', 'status_text'])
                ->make(true);
        }
        $title =  'Skills';
        return view('admin.skill.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkillCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $campaignCategory = $this->skillService->update($request, $request->id);
        } else { //add data
            $campaignCategory  = $this->skillService->store($request);
        }
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $campaignCategory = $this->skillService->show($id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $campaignCategory = $this->skillService->destroy($id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }
}

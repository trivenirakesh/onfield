<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
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
                ->editColumn('name', function($row){
                    return CommonHelper::shortString($row->name,30);
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
            $createUpdateSkill = $this->skillService->update($request, $request->id);
        } else { //add data
            $createUpdateSkill  = $this->skillService->store($request);
        }
        if (!$createUpdateSkill['status']) {
            return $this->jsonResponse($createUpdateSkill, 401);
        }
        return $this->jsonResponse($createUpdateSkill, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getSkillDetails = $this->skillService->show($id);
        if (!$getSkillDetails['status']) {
            return $this->jsonResponse($getSkillDetails, 401);
        }
        return $this->jsonResponse($getSkillDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteSkill = $this->skillService->destroy($id);
        if (!$deleteSkill['status']) {
            return $this->jsonResponse($deleteSkill, 401);
        }
        return $this->jsonResponse($deleteSkill, 200);
    }
}

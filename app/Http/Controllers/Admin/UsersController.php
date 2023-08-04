<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserCreateUpdateRequest;
use App\Models\User;
use App\Services\V1\ManageUserService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    use CommonTrait;
    protected $userService;

    public function __construct(ManageUserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.users.index');
            $data = User::where('id','!=',Auth::user()->id)->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, false);
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->editColumn('username', function($row){
                    return $row->first_name.' '.$row->last_name;
                })
                ->editColumn('role', function($row){
                    return isset($row->role['name']) ? $row->role['name'] : '';
                })
                ->editColumn('user_type', function($row){
                    return $row->user_type;
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'username','email','mobile', 'role','user_type','status_text'])
                ->make(true);
        }
        $title =  'Users';
        return view('admin.users.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateUser = $this->userService->update($request, $request->id);
        } else { //add data
            $createUpdateUser  = $this->userService->store($request);
        }
        if (!$createUpdateUser['status']) {
            return $this->jsonResponse($createUpdateUser, 401);
        }
        return $this->jsonResponse($createUpdateUser, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getUserDetails = $this->userService->show($id);
        if (!$getUserDetails['status']) {
            return $this->jsonResponse($getUserDetails, 401);
        }
        return $this->jsonResponse($getUserDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteUser = $this->userService->destroy($id);
        if (!$deleteUser['status']) {
            return $this->jsonResponse($deleteUser, 401);
        }
        return $this->jsonResponse($deleteUser, 200);
    }
}

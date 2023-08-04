<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserCreateUpdateRequest;
use App\Http\Resources\V1\UserDetailResource;
use App\Services\V1\ManageUserService;

class ManageUserController extends Controller
{
    private $userService;

    public function __construct(ManageUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getUser =  $this->userService->index() ?? [];
        if (!$getUser['status']) {
            return response()->json($getUser, 401);
        }
        return response()->json($getUser, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateUpdateRequest $request)
    {
        $saveUser  = $this->userService->store($request);
        if (!$saveUser['status']) {
            return response()->json($saveUser, 401);
        }
        return response()->json($saveUser, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getUserDetails = $this->userService->show($id);
        $getUserDetails['data'] = new UserDetailResource($getUserDetails['data']);
        if (!$getUserDetails['status']) {
            return response()->json($getUserDetails, 401);
        }
        return response()->json($getUserDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserCreateUpdateRequest $request, $id)
    {
        $updateUser = $this->userService->update($request, $id);
        if (!$updateUser['status']) {
            return response()->json($updateUser, 401);
        }
        return response()->json($updateUser, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteUser = $this->userService->destroy($id);
        if (!$deleteUser['status']) {
            return response()->json($deleteUser, 401);
        }
        return response()->json($deleteUser, 200);
    }
}

<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\UserDetailResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Hash;

class ManageUserService
{
    use CommonTrait;
    const module = 'User';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getProductCategoryData =  UserResource::collection(User::latest('id')->get());
        return $this->successResponseArr(self::module . __('messages.success.list'), $getProductCategoryData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save user section
        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = str_replace(' ', '', $request->email);
        $user->mobile = $request->mobile;
        $user->user_type = $request->user_type;
        $user->status = $request->status;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->created_by = auth()->user()->id;
        $user->created_ip = CommonHelper::getUserIp();
        $user->save();
        return $this->successResponseArr(self::module . __('messages.success.create'), $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $getUplaods = false)
    {
        $getUserData = User::when($getUplaods, function ($query) {
            $query->with(['uploads' => function ($query) {
                $query->select('id', 'reference_id', 'reference_type', 'file', 'media_type', 'file_type','upload_path');
            }]);
        })->where('id', $id)->first();
        if ($getUserData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        return $this->successResponseArr(self::module . __('messages.success.details'), $getUserData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::where('id', $id)->first();
        if ($user == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // update user 
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = str_replace(' ', '', $request->email);
        $user->mobile = $request->mobile;
        $user->role_id = $request->role_id;
        $user->updated_by = auth()->user()->id;
        $user->updated_ip = CommonHelper::getUserIp();
        $user->update();

        $getProductCategoryDetails = new UserDetailResource($user);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getProductCategoryDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user =  User::where('id', $id)->first();
        if ($user == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete user
        $user->deleted_by = auth()->user()->id;
        $user->deleted_ip = CommonHelper::getUserIp();
        $user->update();
        $deleteUser = $user->delete();
        if ($deleteUser) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}

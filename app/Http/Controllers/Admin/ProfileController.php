<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AdminProfileRequest;
use App\Http\Requests\V1\Api\ChangePasswordRequest;
use App\Models\User;
use App\Services\V1\ManageUserService;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use CommonTrait;
    protected $userService;

    public function __construct(ManageUserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $title = 'Profile';
        $user = $this->userService->show(auth()->user()->id);
        if (!$user['status']) {
            return abort(404);
        }
        $user = $user['data'];
        return view('admin.profile', compact('title', 'user'));
    }

    public function update(AdminProfileRequest $request)
    {
        $user = User::where('id', $request->id)->where('user_type', User::USERADMIN)->first();
        if ($user == null) {
            $res = $this->errorResponseArr('User ' . __('messages.validation.not_found'));
            return response()->json($res, 401);
        }
        $data =  $request->validated();
        unset($data['id']);
        $user->update($data);
        $res = $this->successResponseArr('User ' . __('messages.success.update'), []);
        return response()->json($res, 200);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $userId = Auth::user()->id;
        $user = User::where('id', $userId)->first();
        $validatedData['password'] = Hash::make($request->password);
        $user->update($validatedData);
        $res = $this->successResponseArr('Password ' . __('messages.success.update'), []);
        return response()->json($res);
    }
}

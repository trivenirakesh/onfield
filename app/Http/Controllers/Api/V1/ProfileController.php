<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\ChangePasswordRequest;
use App\Http\Requests\V1\Api\EngineerProfileRequest;
use App\Http\Requests\V1\Api\ProfileRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\Upload;
use App\Models\User;
use App\Services\V1\ManageUserService;
use App\Traits\CommonTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ProfileController extends Controller
{
    use CommonTrait;


    public function index()
    {
        try {
            $popularProducts = [];
            $recentServices = [];
            $recentCalls = [];
            $response = [
                'recent_calls' => $recentCalls,
                'recent_services' => $recentServices,
                'popular_products' => $popularProducts,
            ];
            return $this->successResponse('Dashboard' . __('messages.success.details'), $response, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $userService = new ManageUserService;
            $getUserDetails = $userService->show(auth()->id());
            if (!$getUserDetails['status']) {
                return $this->jsonResponse($getUserDetails, 401);
            }
            $getUserDetails['data'] =  new UserResource($getUserDetails['data']);
            return $this->jsonResponse($getUserDetails, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function engineerProfile(Request $request)
    {
        try {
            $userService = new ManageUserService;
            $getUserDetails = $userService->show(auth()->id(), true);
            if (!$getUserDetails['status']) {
                return $this->jsonResponse($getUserDetails, 401);
            }
            $getUserDetails['data'] =  new UserResource($getUserDetails['data']);
            return $this->jsonResponse($getUserDetails, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function engineerProfileUpdate(EngineerProfileRequest $request)
    {

        DB::beginTransaction();
        try {
            $inputs = $request->only('first_name', 'last_name', 'email');
            $user = User::with(['uploads' => function ($query) {
                $query->select('id', 'reference_id', 'reference_type', 'file', 'media_type', 'image_type', 'upload_path');
            }])->where('id', auth()->id())->first();
            $user->update($inputs);
            $userId = $user->id;

            // save engineer profile 
            $uploadPath = User::FOLDERNAME;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $data = CommonHelper::uploadImages($image, User::FOLDERNAME, 1);
                if (!empty($data)) {
                    // delete file if exists
                    if ($user?->profile?->id != null) {
                        // Unlink old image from storage 
                        $oldImage = $user?->profile->getAttributes()['file'] ?? null;
                        $path = $user->profile->upload_path ?? null;
                        if ($oldImage != null && $path != null) {
                            CommonHelper::removeUploadedImages($oldImage, $path);
                        }
                        $user->profile->delete();
                    }

                    $uploadsArr = [
                        'file' => $data['filename'],
                        'thumb_file' => $data['filename'],
                        'media_type' => User::MEDIA_TYPES[0],
                        'image_type' => $data['filetype'],
                        'upload_path' => CommonHelper::getUploadPath($uploadPath),
                        'created_ip' => CommonHelper::getUserIp(),
                        'reference_id' => $userId,
                        'reference_type' => User::class,
                    ];
                    Upload::create($uploadsArr);
                }
            }

            // save engineer address proof 
            if ($request->hasFile('addressproof')) {
                $addressProof = $request->file('addressproof');
                $data = CommonHelper::uploadImages($addressProof, User::FOLDERNAME, 1);
                if (!empty($data)) {
                    // delete file if exists
                    if ($user?->addressproof?->id != null) {
                        // Unlink old image from storage 
                        $oldImage = $user?->addressproof->getAttributes()['file'] ?? null;
                        $path = $user->addressproof->upload_path ?? null;
                        if ($oldImage != null && $path != null) {
                            CommonHelper::removeUploadedImages($oldImage, $path);
                        }
                        $user->addressproof->delete();
                    }
                    $address = [
                        'thumb_file' => $data['filename'],
                        'media_type' => User::MEDIA_TYPES[2],
                        'image_type' => $data['filetype'],
                        'created_ip' => CommonHelper::getUserIp(),
                        'upload_path' => CommonHelper::getUploadPath($uploadPath),
                        'reference_id' => $userId,
                        'reference_type' => User::class,
                        'file' => $data['filename'],
                    ];
                    Upload::create($address);
                }
            }

            // save engineer id proof 
            if ($request->hasFile('idproof')) {
                $IdProof = $request->file('idproof');
                $data = CommonHelper::uploadImages($IdProof, User::FOLDERNAME, 1);
                if (!empty($data)) {
                    // delete file if exists
                    if ($user?->idProof?->id != null) {
                        // Unlink old image from storage 
                        $oldImage = $user?->idProof->getAttributes()['file'] ?? null;
                        $path = $user->idProof->upload_path ?? null;
                        if ($oldImage != null && $path != null) {
                            CommonHelper::removeUploadedImages($oldImage, $path);
                        }
                        $user->idProof->delete();
                    }

                    $idProof = [
                        'file' => $data['filename'],
                        'thumb_file' => $data['filename'],
                        'upload_path' => CommonHelper::getUploadPath($uploadPath),
                        'media_type' => User::MEDIA_TYPES[3],
                        'image_type' => $data['filetype'],
                        'created_ip' => CommonHelper::getUserIp(),
                        'reference_id' => $userId,
                        'reference_type' => User::class,
                    ];
                    Upload::create($idProof);
                }
            }

            // save engineer resume 
            if ($request->hasFile('resume')) {
                $resume = $request->file('resume');
                $data = CommonHelper::uploadFile($resume, User::FOLDERNAME);
                if (!empty($data)) {

                    // delete file if exists
                    if ($user?->resume?->id != null) {
                        // Unlink old image from storage 
                        $oldImage = $user?->resume->getAttributes()['file'] ?? null;
                        $path = $user->resume->upload_path ?? null;
                        if ($oldImage != null && $path != null) {
                            CommonHelper::removeUploadedImages($oldImage, $path);
                        }
                        $user->resume->delete();
                    }

                    $resume = [
                        'file' => $data['filename'],
                        'media_type' => User::MEDIA_TYPES[1],
                        'image_type' => $data['filetype'],
                        'upload_path' => CommonHelper::getUploadPath($uploadPath),
                        'created_ip' => CommonHelper::getUserIp(),
                        'reference_id' => $userId,
                        'reference_type' => User::class,
                    ];
                    Upload::create($resume);
                }
            }
            $getUserDetails = new UserResource($user);
            DB::commit();
            return $this->successResponse('Profile' . __('messages.success.update'), $getUserDetails, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function profileUpdate(ProfileRequest $request)
    {
        try {
            $inputs = $request->validated();
            $user = User::where('id', auth()->id())->first();
            $user->update($inputs);
            $user =  new UserResource($user);
            return $this->successResponse('Profile' . __('messages.success.update'), $user, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $password = bcrypt($request->password);
            $inputs = $request->validated();
            $user = User::where('id', auth()->id())->first();
            $user->update(['password' => $password]);
            return $this->successResponse('Password' . __('messages.success.update'), 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }
}

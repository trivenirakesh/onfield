<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\CommonHelper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\V1\SignUpRequest;
use App\Models\EngineerSkill;
use App\Models\State;
use App\Models\Upload;
use App\Http\Resources\V1\StateResource;
use App\Models\Address;

class AuthController extends Controller
{
    use CommonTrait;
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(SignUpRequest $request)
    {
        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_type' => $request->user_type,
                'status' => 0,
                'created_ip' => CommonHelper::getUserIp(),
                'password' => Hash::make($request->password)
            ]);
            $lastId = $user->id;
            if($request->user_type == 1){
                if ($request->has('skills')) {
                    foreach($request->skills as $key => $val){
                        $saveEngineerSkills = new EngineerSkill();
                        $saveEngineerSkills->user_id = $lastId;
                        $saveEngineerSkills->skill_id = $val;
                        $saveEngineerSkills->created_ip =  CommonHelper::getUserIp();
                        $saveEngineerSkills->save();
                    }
                }

                // save engineer profile 
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $data = CommonHelper::uploadImages($image,User::FOLDERNAME,1);
                    if (!empty($data)) {
                        $saveUploads = new Upload();
                        $saveUploads['file'] = $data['filename'];
                        $saveUploads['thumb_file'] = $data['filename'];
                        $saveUploads['media_type'] = User::MEDIA_TYPES[0];
                        $saveUploads['image_type'] = $data['filetype'];
                        $saveUploads['created_ip'] = CommonHelper::getUserIp();
                        $saveUploads['reference_id'] = $lastId;
                        $saveUploads['reference_type'] = User::class;
                        $saveUploads->save();
                    }
                }

                // save engineer address proof 
                if ($request->hasFile('addressproof')) {
                    $addressProof = $request->file('addressproof');
                    $data = CommonHelper::uploadImages($addressProof,User::FOLDERNAME,1);
                    if (!empty($data)) {
                        $saveUploads = new Upload();
                        $saveUploads['file'] = $data['filename'];
                        $saveUploads['thumb_file'] = $data['filename'];
                        $saveUploads['media_type'] = User::MEDIA_TYPES[2];
                        $saveUploads['image_type'] = $data['filetype'];
                        $saveUploads['created_ip'] = CommonHelper::getUserIp();
                        $saveUploads['reference_id'] = $lastId;
                        $saveUploads['reference_type'] = User::class;
                        $saveUploads->save();
                    }
                }

                // save engineer id proof 
                if ($request->hasFile('idproof')) {
                    $IdProof = $request->file('idproof');
                    $data = CommonHelper::uploadImages($IdProof,User::FOLDERNAME,1);
                    if (!empty($data)) {
                        $saveUploads = new Upload();
                        $saveUploads['file'] = $data['filename'];
                        $saveUploads['thumb_file'] = $data['filename'];
                        $saveUploads['media_type'] = User::MEDIA_TYPES[3];
                        $saveUploads['image_type'] = $data['filetype'];
                        $saveUploads['created_ip'] = CommonHelper::getUserIp();
                        $saveUploads['reference_id'] = $lastId;
                        $saveUploads['reference_type'] = User::class;
                        $saveUploads->save();
                    }
                }

                // save engineer resume 
                if ($request->hasFile('resume')) {
                    $resume = $request->file('resume');
                    $data = CommonHelper::uploadFile($resume,User::FOLDERNAME);
                    if (!empty($data)) {
                        $saveUploads = new Upload();
                        $saveUploads['file'] = $data['filename'];
                        $saveUploads['media_type'] = User::MEDIA_TYPES[1];
                        $saveUploads['image_type'] = $data['filetype'];
                        $saveUploads['created_ip'] = CommonHelper::getUserIp();
                        $saveUploads['reference_id'] = $lastId;
                        $saveUploads['reference_type'] = User::class;
                        $saveUploads->save();
                    }
                }

                // Save engineer address
                if ($request->has('state_id')) {
                    $saveAddress = new Address();
                    $saveAddress->reference_id = $lastId;
                    $saveAddress->address_type_id = 1; // By default current address 
                    $saveAddress->address = $request->address;
                    $saveAddress->state_id = $request->state_id;
                    $saveAddress->city = $request->city;
                    $saveAddress->created_ip = CommonHelper::getUserIp();
                    $saveAddress->save();
                }
            }
            $getUserDetails = new UserResource($user);
            return $this->successResponse($getUserDetails,'User registered successfully',201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'mobile' => 'required|numeric|digits:10',
                'password' => 'required'
            ],
            [
                'mobile.required' => __('messages.validation.mobile'),
                'mobile.numeric' => 'Mobile' . __('messages.validation.must_numeric'),
                'mobile.digits' => __('messages.validation.mobile_digits'),
                'password.required'    => __('messages.validation.password'),
            ]);

            if($validateUser->fails()){
                return $this->errorResponse($validateUser->errors(), 401);
            }

            if(!Auth::attempt($request->only(['mobile', 'password']))){
                return $this->errorResponse(__('messages.validation.mobile_password_wrong'),401);
            }
            $user = User::where('mobile', $request->mobile)->first();
            // $user->tokens()->delete();
            if($user->status == 0){
                return $this->errorResponse(__('messages.auth.account_not_approved'),404);
            }
            $getUserDetails['id'] = $user->id;
            $getUserDetails['username'] = $user->first_name.' '.$user->last_name;
            $getUserDetails['email'] = $user->email;
            $getUserDetails['mobile'] = $user->mobile;
            $getUserDetails['status'] = ($user->status == 1 ? 'Active' : 'Deactive');
            $getUserDetails['token'] = $user->createToken("api")->plainTextToken;
            
            return $this->successResponse($getUserDetails, __('messages.success.user_login'),200);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    public function logout(Request $request){
        try {
            $validateUser = Validator::make($request->all(), [
                'id' => 'required|numeric',
            ],
            [
             'id.required'    => 'Please enter id',
             'id.numeric'    => 'Id must be numeric',
            ]);

            if($validateUser->fails()){
                return $this->errorResponse($validateUser->errors(), 401);
            }
            $user = User::find($request->id);
            if(!$user){
                return $this->errorResponse(__('messages.success.user_not_found'), 404);
            }
            $user->tokens()->delete();
            if($user){
                return $this->successResponse([],__('messages.success.user_logout'),200);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    public function getStates(){
        $getStateData = StateResource::collection(State::latest('id')->get());
        return $this->successResponse($getStateData,__('messages.success.user_logout'),200);
    }
}
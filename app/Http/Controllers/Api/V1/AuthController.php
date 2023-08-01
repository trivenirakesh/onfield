<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\CommonHelper;
use App\Models\Entitymst;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EntityResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\V1\SignUpRequest;
use App\Models\EngineerSkill;

class AuthController extends Controller
{
    use CommonTrait;
    /**
     * Create Entity
     * @param Request $request
     * @return Entitymst 
     */
    public function createEntity(SignUpRequest $request)
    {
        try {
            $user = Entitymst::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'entity_type' => $request->entity_type,
                'status' => 0,
                'created_ip' => CommonHelper::getUserIp(),
                'password' => Hash::make($request->password)
            ]);
            $lastId = $user->id;
            if($request->entity_type == 1){
                if ($request->has('skills')) {
                    foreach($request->skills as $key => $val){
                        $saveEngineerSkills = new EngineerSkill();
                        $saveEngineerSkills->engineer_entity_id = $lastId;
                        $saveEngineerSkills->skill_id = $val;
                        $saveEngineerSkills->created_ip =  CommonHelper::getUserIp();
                        $saveEngineerSkills->save();
                    }
                }
            }
            $getUserDetails = new EntityResource($user);
            return $this->successResponse($getUserDetails,'User registered successfully',201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    /**
     * Login The Entity
     * @param Request $request
     * @return Entitymst
     */
    public function loginEntity(Request $request)
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
            $user = Entitymst::where('mobile', $request->mobile)->first();
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
            $user = Entitymst::find($request->id);
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
}
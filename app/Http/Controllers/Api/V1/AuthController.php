<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\CommonHelper;
use App\Models\Entitymst;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\{ClientRegisterRequest, ClientLoginRequest, ForgotPasswordRequest, VerifyOtpRequest, ResetPasswordRequest, UserSocialLoginRequest};
use App\Http\Resources\V1\EntityResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\CommonTrait;
use App\Models\EngineerSkill;
use Carbon\Carbon;
use Exception;

class AuthController extends Controller
{
    use CommonTrait;
    // /**
    //  * Create Entity
    //  * @param Request $request
    //  * @return Entitymst 
    //  */
    // public function createEntity(SignUpRequest $request)
    // {
    //     try {
    //         $user = Entitymst::create([
    //             'first_name' => $request->first_name,
    //             'last_name' => $request->last_name,
    //             'email' => $request->email,
    //             'mobile' => $request->mobile,
    //             'entity_type' => $request->entity_type,
    //             'status' => 0,
    //             'created_ip' => CommonHelper::getUserIp(),
    //             'password' => Hash::make($request->password)
    //         ]);
    //         $lastId = $user->id;
    //         if($request->entity_type == 1){
    //             if ($request->has('skills')) {
    //                 foreach($request->skills as $key => $val){
    //                     $saveEngineerSkills = new EngineerSkill();
    //                     $saveEngineerSkills->engineer_entity_id = $lastId;
    //                     $saveEngineerSkills->skill_id = $val;
    //                     $saveEngineerSkills->created_ip =  CommonHelper::getUserIp();
    //                     $saveEngineerSkills->save();
    //                 }
    //             }
    //         }
    //         $getUserDetails = new EntityResource($user);
    //         return $this->successResponse($getUserDetails,'User registered successfully',201);
    //     } catch (\Throwable $th) {
    //         Log::error($th->getMessage());
    //         return $this->errorResponse($th->getMessage(),500);
    //     }
    // }

    // /**
    //  * Login The Entity
    //  * @param Request $request
    //  * @return Entitymst
    //  */
    // public function loginEntity(Request $request)
    // {
    //     try {
    //         $validateUser = Validator::make($request->all(), 
    //         [
    //             'mobile' => 'required|numeric|digits:10',
    //             'password' => 'required'
    //         ],
    //         [
    //             'mobile.required' => __('messages.validation.mobile'),
    //             'mobile.numeric' => 'Mobile' . __('messages.validation.must_numeric'),
    //             'mobile.digits' => __('messages.validation.mobile_digits'),
    //             'password.required'    => __('messages.validation.password'),
    //         ]);

    //         if($validateUser->fails()){
    //             return $this->errorResponse($validateUser->errors(), 401);
    //         }

    //         if(!Auth::attempt($request->only(['mobile', 'password']))){
    //             return $this->errorResponse(__('messages.validation.mobile_password_wrong'),401);
    //         }
    //         $user = Entitymst::where('mobile', $request->mobile)->first();
    //         // $user->tokens()->delete();
    //         if($user->status == 0){
    //             return $this->errorResponse(__('messages.auth.account_not_approved'),404);
    //         }
    //         $getUserDetails['id'] = $user->id;
    //         $getUserDetails['username'] = $user->first_name.' '.$user->last_name;
    //         $getUserDetails['email'] = $user->email;
    //         $getUserDetails['mobile'] = $user->mobile;
    //         $getUserDetails['status'] = ($user->status == 1 ? 'Active' : 'Deactive');
    //         $getUserDetails['token'] = $user->createToken("api")->plainTextToken;

    //         return $this->successResponse($getUserDetails, __('messages.success.user_login'),200);

    //     } catch (\Throwable $th) {
    //         Log::error($th->getMessage());
    //         return $this->errorResponse($th->getMessage(),500);
    //     }
    // }


    public function register(ClientRegisterRequest $request)
    {

        try {

            $inputs = $request->validated();
            $input['entiy_type'] = Entitymst::ENTITYCLIENT;
            $input['status'] = 0;
            $input['password'] = Hash::make($inputs->password);
            $user = Entitymst::create($inputs);
            $userId = $user->id;
            $skills = [];
            $datetime = now()->format('Y-m-d H:i:s');
            if ($request->entity_type == 1) {
                if ($request->has('skills')) {
                    foreach ($request->skills as $key => $val) {
                        $skillArr = [
                            'engineer_entity_id' => $userId,
                            'skill_id' => $val,
                            'created_ip' =>  CommonHelper::getUserIp(),
                            'created_at' => $datetime,
                            'updated_at' => $datetime,
                        ];
                        $skills[] = $skillArr;
                    }
                    if (!empty($skills)) {
                        EngineerSkill::insert($skills);
                    }
                }
            }
            $getUserDetails = new EntityResource($user);
            return $this->successResponse($getUserDetails, 'User registered successfully', 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    // public function EmailVerification($id)
    // {
    //     $user = User::where('id', Crypt::decrypt($id))->where('email_verified_at', null)->first();
    //     if ($user == null) {
    //         abort(404);
    //     }
    //     $user->update(['email_verified_at' => date('Y-m-d H:i:s')]);
    //     return view('account-verified-success');
    // }

    // public function ForgotPasword(ForgotPasswordRequest $request)
    // {
    //     try {
    //         $post = $request->validated();
    //         $user = User::userRole(true)->where('email', $post['email'])->first();
    //         if ($user == null) {
    //             return error('No user found with this email!');
    //         }
    //         $otp = PasswordReset::GenerateOtp();
    //         $expireTime = Carbon::now()->subMinute(PasswordReset::EXPIRE)->toDateTimeString();
    //         PasswordReset::where('email', $request->email) //delete expired token
    //             ->where('created_at', '<', $expireTime)
    //             ->delete();
    //         PasswordReset::create(['email' => $request->email, 'token' => $otp]);
    //         $mailData = [
    //             'name'      =>  $user->name,
    //             'otp'       => $otp,
    //             'expire'    => PasswordReset::EXPIRE,
    //         ];
    //         Mail::to($request->email)->send(new ForgotPasswordMail($mailData));
    //         return success('Forgot password OTP sent on your email.');
    //     } catch (Exception $e) {
    //         return error('Something went wrong', $e->getMessage());
    //     }
    // }

    // public function verifyOtp(VerifyOtpRequest $request)
    // {
    //     try {
    //         $validData = $request->validated();
    //         $otp = PasswordReset::checkOtp($validData);
    //         $res = $otp ? success("OTP verified successfully.") : error('Invalid OTP');
    //         return $res;
    //     } catch (Exception $e) {
    //         return error('Something went wrong', $e->getMessage());
    //     }
    // }

    // public function resetPassword(ResetPasswordRequest $request)
    // {
    //     try {
    //         $validData = $request->validated();
    //         $user = User::userRole(true)->where('email', $validData['email'])->first();
    //         $otpVerified = PasswordReset::checkOtp($validData);
    //         if ($user == null || !$otpVerified) {
    //             return error('Invalide request');
    //         }
    //         $udateData['password'] = $validData['password'];
    //         $user->update($udateData);
    //         PasswordReset::where('email', $validData['email'])->delete();
    //         return success('Passowrd reset successfully.');
    //     } catch (Exception $e) {
    //         return error('Something went wrong', $e->getMessage());
    //     }
    // }

    public function login(ClientLoginRequest $request)
    {

        try {
            $validData = $request->validated();
            if (!Auth::attempt($request->only(['mobile', 'password']))) {
                return $this->errorResponse(__('messages.validation.mobile_password_wrong'), 401);
            }
            $user = Entitymst::where('mobile', $request->mobile)->first();
            if ($user->status == 0) {
                return $this->errorResponse(__('messages.auth.account_not_approved'), 404);
            }
            $getUserDetails  = $user->loginResponse();
            $objToken = $user->createToken('API Access');
            $expiration = $objToken->token->expires_at->diffInSeconds(Carbon::now());
            $tokenBody =  [
                'expires_in' => $expiration,
                'access_token' => $objToken->accessToken
            ];
            $response = [
                'status' => true,
                'message' => __('messages.success.user_login'),
                'auth_token' => $tokenBody,
                'data' => $getUserDetails,
            ];

            return response()->json($response, 200);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return  $this->successResponse('Successfully logout');
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
}

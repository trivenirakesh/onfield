<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\CommonHelper;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\{ClientRegisterRequest, ClientLoginRequest, ForgotPasswordRequest, ResendOtpRequest, VerifyOtpRequest, ResetPasswordRequest};
use App\Http\Requests\V1\SignUpRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\CommonTrait;
use App\Models\EngineerSkill;
use App\Models\Upload;
use App\Models\Address;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Notifications\ForgotPasswordNotification;
use App\Notifications\VerifyAccountOTP;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    use CommonTrait;


    public function engineerRegister(SignUpRequest $request)
    {

        try {
            $inputs = $request->validated();
            $input['entity_type'] = User::USERENGINEER;
            $input['status'] = 0;
            $input['password'] = Hash::make($inputs->password);
            $user = User::create($inputs);
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

                // save engineer profile 
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $data = CommonHelper::uploadImages($image, User::FOLDERNAME, 1);
                    if (!empty($data)) {
                        $saveUploads = new Upload();
                        $saveUploads['file'] = $data['filename'];
                        $saveUploads['thumb_file'] = $data['filename'];
                        $saveUploads['media_type'] = User::MEDIA_TYPES[0];
                        $saveUploads['image_type'] = $data['filetype'];
                        $saveUploads['created_ip'] = CommonHelper::getUserIp();
                        $saveUploads['reference_id'] = $userId;
                        $saveUploads['reference_type'] = User::class;
                        $saveUploads->save();
                    }
                }

                // save engineer address proof 
                if ($request->hasFile('addressproof')) {
                    $addressProof = $request->file('addressproof');
                    $data = CommonHelper::uploadImages($addressProof, User::FOLDERNAME, 1);
                    if (!empty($data)) {
                        $saveUploads = new Upload();
                        $saveUploads['file'] = $data['filename'];
                        $saveUploads['thumb_file'] = $data['filename'];
                        $saveUploads['media_type'] = User::MEDIA_TYPES[2];
                        $saveUploads['image_type'] = $data['filetype'];
                        $saveUploads['created_ip'] = CommonHelper::getUserIp();
                        $saveUploads['reference_id'] = $userId;
                        $saveUploads['reference_type'] = User::class;
                        $saveUploads->save();
                    }
                }

                // save engineer id proof 
                if ($request->hasFile('idproof')) {
                    $IdProof = $request->file('idproof');
                    $data = CommonHelper::uploadImages($IdProof, User::FOLDERNAME, 1);
                    if (!empty($data)) {
                        $saveUploads = new Upload();
                        $saveUploads['file'] = $data['filename'];
                        $saveUploads['thumb_file'] = $data['filename'];
                        $saveUploads['media_type'] = User::MEDIA_TYPES[3];
                        $saveUploads['image_type'] = $data['filetype'];
                        $saveUploads['created_ip'] = CommonHelper::getUserIp();
                        $saveUploads['reference_id'] = $userId;
                        $saveUploads['reference_type'] = User::class;
                        $saveUploads->save();
                    }
                }

                // save engineer resume 
                if ($request->hasFile('resume')) {
                    $resume = $request->file('resume');
                    $data = CommonHelper::uploadFile($resume, User::FOLDERNAME);
                    if (!empty($data)) {
                        $saveUploads = new Upload();
                        $saveUploads['file'] = $data['filename'];
                        $saveUploads['media_type'] = User::MEDIA_TYPES[1];
                        $saveUploads['image_type'] = $data['filetype'];
                        $saveUploads['created_ip'] = CommonHelper::getUserIp();
                        $saveUploads['reference_id'] = $userId;
                        $saveUploads['reference_type'] = User::class;
                        $saveUploads->save();
                    }
                }

                // Save engineer address
                if ($request->has('state_id')) {
                    $saveAddress = new Address();
                    $saveAddress->reference_id = $userId;
                    $saveAddress->address_type_id = 1; // By default current address 
                    $saveAddress->address = $request->address;
                    $saveAddress->state_id = $request->state_id;
                    $saveAddress->city = $request->city;
                    $saveAddress->created_ip = CommonHelper::getUserIp();
                    $saveAddress->save();
                }
            }
            $getUserDetails = new UserResource($user);
            return $this->successResponse($getUserDetails, 'User registered successfully', 201);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function clientRegister(ClientRegisterRequest $request)
    {

        try {
            $inputs = $request->validated();
            $inputs['user_type'] = User::USERCLIENT;
            $inputs['status'] = 1;
            $inputs['role_id'] = Role::USERROLE;
            $inputs['password'] = Hash::make($inputs['password']);
            $otp = 123456;  //dummy otp will rmeove after actual sms integration setup
            // $otp =  User::generateOtp();
            $inputs['otp'] = $otp;
            $user = User::create($inputs);
            if ($request->city != null) {
                $addrInputData = [
                    'user_id' => $user->id,
                    'address_type_id' => 1,
                    'state_id' => $request->state_id,
                    'address' => "-",
                    'city' => $request->city,
                    'created_ip' => CommonHelper::getUserIp(),
                ];
                Address::create($addrInputData);
            }
            $getUserDetails = new UserResource($user);

            //send opt  user mobile
            $user->notify(new VerifyAccountOTP($otp));
            //send opt  user mobile end

            return $this->successResponse(__('messages.success.register_successfully'), $getUserDetails, 200);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function resendVerificationOtp(ResendOtpRequest $request)
    {
        try {

            $user = User::where('mobile', $request->mobile)->first();
            if ($user == null) {
                return $this->errorResponse('User ' . __('messages.validation.not_found'), 404);
            }
            if ($user->is_otp_verify == 1) {
                return response()->json(['status' => false, 'message' => __('messages.auth.account_already_verified')], 401);
            }
            // $otp =  User::generateOtp();
            $otp = 123456;  //dummy otp will rmeove after actual sms integration setup
            $user->update(['otp' => $otp]);

            //send opt  user mobile
            $user->notify(new VerifyAccountOTP($otp));
            //send opt  user mobile end
        } catch (Exception $e) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
        return $this->successResponse(__('messages.success.verification_otp_Send_successfully'), 200);
    }

    public function otpVerification(VerifyOtpRequest $request)
    {
        try {
            $user = User::where('mobile', $request->mobile)->first();
            if ($user == null) {
                return $this->errorResponse('User ' . __('messages.validation.not_found'), 404);
            }
            if ($user->is_otp_verify == 1) {
                return response()->json(['status' => false, 'message' => __('messages.auth.account_already_verified')], 401);
            }
            if ($user->otp != $request->otp) {
                return $this->errorResponse(__('messages.auth.invalid_otp'), 401);
            }
            $user->update(['is_otp_verify' => 1, 'otp_verified_at' => now()->format('Y-m-d H:i:s')]);
        } catch (Exception $e) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
        return $this->successResponse(__('messages.success.account_verified'), 200);
    }

    public function forgotPasaword(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('mobile', $request->mobile)->first();
            if ($user == null) {
                return $this->errorResponse('User ' . __('messages.validation.not_found'), 404);
            }

            $otp = 123456; //dummy otp will rmeove after actual sms integration setup
            // $otp = PasswordReset::GenerateOtp();
            $expireTime = Carbon::now()->subMinute(PasswordReset::EXPIRE)->toDateTimeString();
            PasswordReset::where('email', $request->email) //delete expired token
                ->where('created_at', '<', $expireTime)
                ->delete();
            PasswordReset::create(['email' => $request->mobile, 'token' => $otp]);
            $mailData = [
                'name'      =>  $user->first_name . ' ' . $user->last_name,
                'otp'       => $otp,
                'expire'    => PasswordReset::EXPIRE,
            ];
            $user->notify(new ForgotPasswordNotification($mailData));

            return $this->successResponse(__('messages.success.forgot_password_otp'));
        } catch (Exception $e) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $validData = $request->validated();
            $user = User::where('mobile', $validData['mobile'])->first();
            if ($user == null) {
                return $this->errorResponse('User ' . __('messages.validation.not_found'), 404);
            }
            $otpVerified = PasswordReset::checkOtp($validData);
            if (!$otpVerified) {
                return $this->errorResponse(__('messages.auth.invalid_otp'), 401);
            }
            $udateData = ['password' => bcrypt($validData['password'])];
            $user->update($udateData);
            PasswordReset::where('email', $validData['mobile'])->delete();
            return $this->successResponse(__('messages.success.password_reset'));
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function login(ClientLoginRequest $request)
    {
        try {
            $validData = $request->validated();
            if (!Auth::attempt($request->only(['mobile', 'password']))) {
                return $this->errorResponse(__('messages.validation.mobile_password_wrong'), 401);
            }
            $user = User::where('mobile', $request->mobile)->first();
            if ($user->status == 0) {
                return $this->errorResponse(__('messages.auth.account_not_approved'), 401);
            }
            if ($user->is_otp_verify == 0) {
                return $this->errorResponse(__('messages.auth.account_not_verified'), 401);
            }
            $getUserDetails  = $user->loginResponse();
            $objToken = $user->createToken('API Access');
            $expiration = $objToken->token->expires_at->diffInSeconds(Carbon::now());
            $tokenBody =  [
                'expires_in' => $expiration,
                'access_token' => $objToken->accessToken
            ];
            $response = [
                'auth_token' => $tokenBody,
                'user' => $getUserDetails,
            ];
            return  $this->successResponse(__('messages.success.user_login'), $response);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user()->token();
            $user->revoke();
            return  $this->successResponse('Account logout successfully.');
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }
}

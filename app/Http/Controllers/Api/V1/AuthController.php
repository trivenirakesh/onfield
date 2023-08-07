<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\CommonHelper;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\{ClientRegisterRequest, ClientLoginRequest, EngineerRegisterRequest, ForgotPasswordRequest, RefreshTokenRequest, ResendOtpRequest, VerifyOtpRequest, ResetPasswordRequest};
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\RefreshTokenRepository;

class AuthController extends Controller
{
    use CommonTrait;


    public function engineerRegister(EngineerRegisterRequest $request)
    {

        DB::beginTransaction();
        try {

            $inputs = $request->only('first_name', 'last_name', 'email', 'mobile', 'password');
            $inputs['user_type'] = User::USERENGINEER;
            $inputs['status'] = 0;
            $inputs['password'] = Hash::make($inputs['password']);
            $user = User::create($inputs);
            $userId = $user->id;
            $skills = [];
            $datetime = now()->format('Y-m-d H:i:s');
            if ($request->has('skills')) {
                foreach ($request->skills as $key => $val) {
                    $skillArr = [
                        'user_id' => $userId,
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
                    $uploadsArr = [
                        'file' => $data['filename'],
                        'file' => $data['filename'],
                        'thumb_file' => $data['filename'],
                        'media_type' => User::MEDIA_TYPES[0],
                        'image_type' => $data['filetype'],
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
                    $address = [
                        'thumb_file' => $data['filename'],
                        'media_type' => User::MEDIA_TYPES[2],
                        'image_type' => $data['filetype'],
                        'created_ip' => CommonHelper::getUserIp(),
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
                    $idProof = [
                        'file' => $data['filename'],
                        'thumb_file' => $data['filename'],
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
                    $resume = [
                        'file' => $data['filename'],
                        'media_type' => User::MEDIA_TYPES[1],
                        'image_type' => $data['filetype'],
                        'created_ip' => CommonHelper::getUserIp(),
                        'reference_id' => $userId,
                        'reference_type' => User::class,
                    ];
                    Upload::create($resume);
                }
            }

            // Save engineer address
            if ($request->has('state_id')) {
                $fullAddress = [
                    'user_id' => $userId,
                    'address_type_id' => 1, // By default current address 
                    'address' => $request->address,
                    'state_id' => $request->state_id,
                    'city' => $request->city,
                    'created_ip' => CommonHelper::getUserIp(),
                ];
                $add = Address::create($fullAddress);
            }
            $getUserDetails = new UserResource($user);
            DB::commit();
            return $this->successResponse($getUserDetails, 'User registered successfully', 201);
        } catch (\Throwable $th) {
            DB::rollback();
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

    public function forgotPassword(ForgotPasswordRequest $request)
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
            $getUserDetails = new UserResource($user);
            // $objToken = $user->createToken('example');
            // $expiration = $objToken->token->expires_at->diffInSeconds(Carbon::now());
            // $tokenBody =  [
            //     'expires_in' => $expiration,
            //     'access_token' => $objToken->accessToken
            // ];
            $baseUrl = url('/');
            $response = Http::post("{$baseUrl}/oauth/token", [
                'username' => $request->mobile,
                'password' => $request->password,
                'client_id' => config('passport.password_grant_client.id'),
                'client_secret' => config('passport.password_grant_client.secret'),
                'grant_type' => 'password'
            ]);

            $tokenBody = json_decode($response->getBody(), true);
            $response = [
                'auth_token' => $tokenBody,
                'user' => $getUserDetails,
            ];
            return  $this->successResponse(__('messages.success.user_login'), $response);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        try {
            $baseUrl = url('/');
            $response = Http::post("{$baseUrl}/oauth/token", [
                'refresh_token' => $request->refresh_token,
                'client_id' => config('passport.password_grant_client.id'),
                'client_secret' => config('passport.password_grant_client.secret'),
                'grant_type' => 'refresh_token'
            ]);

            $result = json_decode($response->getBody(), true);
            if (!$response->ok()) {
                return $this->errorResponse($result['error_description'], 401);
            }
        } catch (\Throwable $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
        return  $this->successResponse(__('messages.success.access_token_success'), $result);
    }

    public function logout(Request $request)
    {
        try {
            $token = Auth::user()->token();
            /* --------------------------- revoke access token -------------------------- */
            $token->revoke();
            $token->delete();

            /* -------------------------- revoke refresh token -------------------------- */
            $refreshTokenRepository = app(RefreshTokenRepository::class);
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
            return  $this->successResponse('Account logout successfully.');
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }
}

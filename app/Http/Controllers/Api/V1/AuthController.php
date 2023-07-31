<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Entitymst;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EntityResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use CommonTrait;
    /**
     * Create Entity
     * @param Request $request
     * @return Entitymst 
     */
    public function createEntity(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'type' => 'required|digits:1|lte:3',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:entitymst,email,NULL,id,deleted_at,NULL',
                'mobile' => 'required|numeric|digits:10|unique:entitymst,mobile,NULL,id,deleted_at,NULL',
                'password' => 'required'
            ],[
                'type.required' => 'Please enter type',
                'type.digits' => 'Type value must be numeric',
                'type.lte' => 'Type value must between 0 and 3',
                'first_name.required' => 'Please enter first name',
                'last_name.required' => 'Please enter last name',
                'email.required' => 'Please enter email',
                'email.email' => 'Invaild email address',
                'email.unique' => 'Email address is already registered. Please, use a different email',
                'mobile.required' => 'Please enter mobile',
                'mobile.numeric' => 'Mobile must be numeric',
                'mobile.digits' => 'Mobile should be 10 digit number',
                'mobile.unique' => 'Mobile number is already registered. Please, use a different mobile',
                'password.required' => 'Please enter password',
            ]);

            if($validateUser->fails()){
                return $this->errorResponse($validateUser->errors(), 401);
            }

            // strong password validation 
            $password = str_replace(' ', '', $request->password);
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);
            if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                $errorMessage = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number and one special character.';
                return $this->errorResponse($errorMessage, 400);
            }

            // remoeve blank spaces from string 
            $firstName = ucfirst(strtolower(str_replace(' ', '',$request->first_name))); 
            $lastName = ucfirst(strtolower(str_replace(' ', '',$request->last_name))); 
            
            $user = Entitymst::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'entity_type' => $request->type,
                'status' => 1,
                'password' => Hash::make($password)
            ]);

            //$user['token'] = $user->createToken("API TOKEN")->plainTextToken;
            $lastId = $user->id;
            $getUserDetails = EntityResource::collection(Entitymst::where('id',$lastId)->get());
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
                'mobile.required' => 'Please enter mobile',
                'mobile.numeric' => 'Mobile must be numeric',
                'mobile.digits' => 'Mobile should be 10 digit number',
                'password.required'    => 'Please enter password',
            ]);

            if($validateUser->fails()){
                return $this->errorResponse($validateUser->errors(), 401);
            }

            if(!Auth::attempt($request->only(['mobile', 'password']))){
                return $this->errorResponse('Mobile or password you entered did not match our records.',401);
            }
            $user = Entitymst::where('mobile', $request->mobile)->first();
            $user->tokens()->delete();
            $getUserDetails['id'] = $user->id;
            $getUserDetails['username'] = $user->first_name.' '.$user->last_name;
            $getUserDetails['email'] = $user->email;
            $getUserDetails['mobile'] = $user->mobile;
            $getUserDetails['status'] = ($user->status == 1 ? 'Active' : 'Deactive');
            $getUserDetails['token'] = $user->createToken("api")->plainTextToken;
            
            return $this->successResponse($getUserDetails,'User successfully logged in',200);

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
                return $this->errorResponse('User not found', 404);
            }
            $user->tokens()->delete();
            if($user){
                return $this->successResponse([],'User logout successfully',200);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse($th->getMessage(),500);
        }
    }
}
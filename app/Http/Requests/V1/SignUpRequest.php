<?php

namespace App\Http\Requests\V1;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'entity_type' => ['required',Rule::in([1,2])],
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'email' => 'required|email|unique:entitymst,email,NULL,id,deleted_at,NULL',
            'mobile' => 'required|numeric|digits:10|unique:entitymst,mobile,NULL,id,deleted_at,NULL',
            'password' => [ 'required', 'min:8','regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/','regex:/[@$!%*#?&]/']
        ];

        if(request('entity_type') == 1){
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $rules['addressproof'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $rules['idproof'] = 'required|max:2048|mimes:jpg,png,jpeg';
            $rules['resume'] = 'required|max:2048|mimes:pdf';
            $rules['state_id'] = ['required',Rule::exists('states','id')];
            $rules['city'] = 'required';
            $rules['address'] = 'required';
            $rules['skills.*'] = [
                'required',
                Rule::exists('skills','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                }),
            ];
        }

        return $rules;
    }

    public function messages() : array
    {
        $messages = [
            'first_name.required' => __('messages.validation.first_name'),
            'first_name.max' => __('messages.validation.max'),
            'last_name.required' => __('messages.validation.last_name'),
            'last_name.max' => __('messages.validation.max'),
            'email.required' => __('messages.validation.email'),
            'email.email' => __('messages.validation.email_email'),
            'email.unique' => __('messages.validation.email_unique'),
            'mobile.required' => __('messages.validation.mobile'),
            'mobile.numeric' => 'Mobile' . __('messages.validation.must_numeric'),
            'mobile.digits' => __('messages.validation.mobile_digits'),
            'mobile.unique' => __('messages.validation.mobile_unique'),
            'entity_type.required' => __('messages.validation.entity_type'),
            'entity_type.in' => __('messages.validation.entity_type_in'),
            'password.required' => __('messages.validation.password'),
            'password.min' => __('messages.validation.new_password_min'),
            'password.regex' => __('messages.validation.strong_password'),
        ];

        if(request('entity_type') == 1){
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] =  __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');

            $messages['addressproof.required'] = __('messages.signup.addressproof');
            $messages['addressproof.max'] =  __('messages.signup.addressproof-max');
            $messages['addressproof.mimes'] = __('messages.signup.addressproof-mimes');
            $messages['idproof.required'] = __('messages.signup.idproof');
            $messages['idproof.max'] =  __('messages.signup.idproof-max');
            $messages['idproof.mimes'] = __('messages.signup.idproof-mimes');
            $messages['resume.required'] = __('messages.signup.resume');
            $messages['resume.max'] =  __('messages.signup.resume-max');
            $messages['resume.mimes'] = __('messages.signup.resume-mimes');
            $messages['state_id.required'] =  __('messages.signup.state_id');
            $messages['state_id.exists'] = 'State'.__('messages.validation.not_found');
            $messages['city.required'] = __('messages.signup.city');
            $messages['address.required'] = __('messages.signup.address');
            $messages['skills.*.required'] = __('messages.validation.skill_required');
            $messages['skills.*.exists'] = 'Skill'.__('messages.validation.not_found');
        }
        
        return $messages;
    }
}

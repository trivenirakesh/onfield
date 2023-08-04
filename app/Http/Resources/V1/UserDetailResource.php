<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->first_name.' '.$this->last_name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'is_otp_verify' => ($this->is_otp_verify == 1 ? 'Verify' : 'Not Verify'),
            'otp_verified_at' => !empty($this->otp_verified_at) ? $this->otp_verified_at : '',
            'is_email_verify' => $this->is_email_verify,
            'email_verified_at' => !empty($this->email_verified_at) ? $this->email_verified_at : '',
            'user_type' => $this->user_type,
            'user_type_name' => $this->getUserTypeName($this->user_type),
            'role_id' => ($this->role_id != NULL) ? (string)$this->role_id : '',
            'role_name' => isset($this->role['name']) ? $this->role['name'] : '',
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }

    public function getUserTypeName($type){
        $str = '';
        if($type == 0){
            $str = 'Back Office';
        }elseif($type == 1){
            $str = 'Engineer';
        }elseif($type == 2){
            $str = 'Client';
        }else{
            $str = 'Vendor';
        }
        return $str;
    } 
}

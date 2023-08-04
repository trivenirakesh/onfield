<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    const USERADMIN = 0;
    const USERENGINEER = 1;
    const USERCLIENT = 2;
    const USERVENDOR = 3;

    const MEDIA_TYPES = [
        0 => 'PROFILE',
        1 => 'RESUME',
        2 => 'ADDRESS-PROOF',
        3 => 'ID-PROOF',
    ];

    const FOLDERNAME = "user/";

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name','last_name', 'email','mobile', 'user_type','password', 'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(){
        $getActiveStatus = CommonHelper::getConfigValue('status.active');
        return $this->belongsTo(Role::class,'role_id')->where('status',$getActiveStatus);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));    
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));    
    }

    public function item(){
        return $this->hasOne(Item::class);
    }
}

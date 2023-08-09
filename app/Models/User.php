<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\CommonHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\Client as OClient;
use Laravel\Passport\HasApiTokens;
use GuzzleHttp\Client;


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
        'first_name', 'last_name', 'email', 'mobile', 'user_type', 'password', 'status', 'otp', 'is_otp_verify', 'otp_verified_at'
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

    public function findForPassport($username)
    {
        return self::where('mobile', $username)->first(); // change column name whatever you use in credentials
    }

    public function role()
    {
        $getActiveStatus = CommonHelper::getConfigValue('status.active');
        return $this->belongsTo(Role::class, 'role_id')->where('status', $getActiveStatus);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public static function generateOtp()
    {
        $otp = rand(100000, 999999);
        $token = self::where('otp', $otp)->first();
        if ($token != null) {
            self::generateOtp();
        }
        return $otp;
    }

    //morphic relationship start
    public function uploads()
    {
        return $this->morphMany(Upload::class, 'reference');
    }

    public function profile()
    {
        return $this->morphOne(Upload::class, 'reference')->where('media_type', self::MEDIA_TYPES[0])->latest();
    }

    public function addressProof()
    {
        return $this->morphOne(Upload::class, 'reference')->where('media_type', self::MEDIA_TYPES[2])->latest();
    }

    public function resume()
    {
        return $this->morphOne(Upload::class, 'reference')->where('media_type', self::MEDIA_TYPES[1])->latest();
    }

    public function idProof()
    {
        return $this->morphOne(Upload::class, 'reference')->where('media_type', self::MEDIA_TYPES[3])->latest();
    }

    //morphic relationship end
}

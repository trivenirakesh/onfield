<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\CommonHelper;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\Client as OClient;
use Laravel\Passport\HasApiTokens;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;


class Entitymst extends Authenticatable
{
    const ENTITYADMIN = 0;
    const ENTITYENGINEER = 1;
    const ENTITYCLIENT = 2;
    const ENTITYVENDOR = 3;

    const MEDIA_TYPES = [
        0 => 'PROFILE',
        1 => 'RESUME',
        2 => 'ADDRESS-PROOF',
        3 => 'ID-PROOF',
    ];

    protected $table = 'entitymst';
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile', 'entity_type', 'password', 'status'
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

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public static function getTokenAndRefreshToken($mobile, $password)
    {
        try {
            //code...

            $oClient = OClient::where('password_client', 1)->first();
            if (empty($oClient)) {
                throw new Exception('password_client not found');
            }
            $http = new Client();
            $response = $http->request('POST', url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'username' => $mobile,
                    'password' => $password,
                    'scope' => '*',
                ],
                'verify' => false,
            ]);
        } catch (\Throwable $th) {
            throw new Exception('password_client not found');
        }
        return json_decode((string) $response->getBody(), true);
    }

    public function loginResponse()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile' => $this->image,
            'image' => $this->image,
            'status' => $this->status == 1 ? 'Active' : 'Deactive'
        ];
    }
}

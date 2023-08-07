<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
    public function address_type()
    {
        return $this->hasOne(AddressType::class, 'id', 'address_type_id');
    }
}

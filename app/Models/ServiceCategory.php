<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];
    
    const FOLDERNAME = "servicecategory/";

    const MEDIA_TYPES = [
      0 => 'BASE',
  ];

    public function upload() {
      return $this->morphOne(Upload::class, 'reference')->where('media_type',self::MEDIA_TYPES[0])->latest();
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));
    }
}

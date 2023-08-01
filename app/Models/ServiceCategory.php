<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use HasFactory,SoftDeletes;
    
    const FOLDERNAME = "servicecategory/";

    const MEDIA_TYPES = [
      0 => 'BASE',
  ];

    public function images() {
      return $this->morphMany(Upload::class, 'reference');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));
    }
}

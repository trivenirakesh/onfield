<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    const FOLDERNAME = "service/";

    const MEDIA_TYPES = [
        0 => 'BASE',
    ];

    public function images()
    {
        return $this->morphMany(Upload::class, 'reference');
    }

    public function service_category()
    {
        return $this->hasOne(ServiceCategory::class, 'id', 'service_category_id');
    }
}

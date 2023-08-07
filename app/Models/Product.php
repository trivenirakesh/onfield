<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    const FOLDERNAME = "product/";

    const MEDIA_TYPES = [
        0 => 'BASE',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = preg_replace('/\s+/', ' ', ucfirst(strtolower($value)));
    }
    
    public function images() {
        return $this->morphMany(Upload::class, 'reference');
    }

    public function unitOfMeasurement(){
        return $this->belongsTo(UnitOfMeasurement::class,'unit_of_measurement_id')->where('status',CommonHelper::getConfigValue('status.active'));
    }

    public function productCategory(){
        return $this->belongsTo(ProductCategory::class,'product_category_id')->where('status',CommonHelper::getConfigValue('status.active'));
    }

    public function user(){
        return $this->belongsTo(User::class,'vendor_id')->where('status',CommonHelper::getConfigValue('status.active'));
    }
}

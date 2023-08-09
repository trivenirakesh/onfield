<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
  use HasFactory, SoftDeletes;

  protected $guarded = ['id'];

  public function reference()
  {
    return $this->morphTo();
  }

  public function getFileAttribute($file)
  {
    $imageUrl = 'public/dist/img/no-image.png';
    if (!empty($file)) {
      $imageUrl = $this->upload_path . '' . $file;
    }
    return asset($imageUrl);
  }
}

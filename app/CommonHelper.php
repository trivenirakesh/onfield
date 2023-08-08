<?php 

namespace App\Helpers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload;
use DateTime;
use DateTimeZone;
use Image;

class CommonHelper{

    public static function getUserIp(){
        return request()->ip();
    }

    public static function getUTCDateTime($dateTime){

        $dateTime = date("Y-m-d H:i:s",strtotime($dateTime)); 
        $newDateTime = new DateTime($dateTime); 
        $newDateTime->setTimezone(new DateTimeZone("UTC")); 
        $dateTimeUTC = $newDateTime->format("Y-m-d H:i:s");
        return $dateTimeUTC;
    }

    public static function getConvertedDateTime($dateTime,$format = ''){

        $siteTimeZone = Config::get('constants.site_timezone');
        $dateTime = date("Y-m-d H:i:s",strtotime($dateTime)); 
        $newDateTime = new DateTime($dateTime); 
        $newDateTime->setTimezone(new DateTimeZone($siteTimeZone)); 
        if(!empty($format)){
            $dateTimeUTC = $newDateTime->format($format);
        }else{
            $dateTimeUTC = $newDateTime->format("Y-m-d H:i A");
        }
        return $dateTimeUTC;
    }

    public static function uploadImages($file,$path,$type = null){ 
        $uploadPath = self::getConfigValue('upload_path').$path;
        $fileType = $file->getClientMimeType();
        if($fileType == 'image/png' || $fileType == 'image/jpeg'){
            $fileType = 'image';
        }
        $fileName = '';
        // Upload base image
        if($type != 0){
            $imagePath = $file->store($uploadPath);
            $fileName = basename($imagePath);

            $uploadPath = $uploadPath.'thumb/'; // folder path for thumb files
        }
        // Upload base image

        // start thumb image $type = 0 make thumb
        if($type == 0 && empty($fileName)){
            $fileName = \Illuminate\Support\Str::random(40).'.'.$file->extension();
        }
        $img = Image::make($file->getRealPath());
        $img->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();                 
        });
        $img->stream();
        
        Storage::disk('local')->put($uploadPath.$fileName, $img, 'public');
        // start thumb image
      
        $responseArr['filename'] = $fileName;
        $responseArr['filetype'] = $fileType;
        return $responseArr;
    }

    public static function uploadFile($file,$path){
        $uploadPath = self::getConfigValue('upload_path').$path;
        $fileType = $file->getClientMimeType();
        $imagePath = $file->store($uploadPath);
        $fileName = basename($imagePath);
        if($fileName == 'application/pdf' || $fileName == 'application/msword'){
            $fileName = 'file';
        }
        $responseArr['filename'] = $fileName;
        $responseArr['filetype'] = $fileType;
        return $responseArr;
    }

    public static function getImageUrl($filename,$path,$type){
        $imageUrl = '';
        $linkPath = self::getConfigValue('link_path');
        if($type == 0){
            $imageUrl = asset($linkPath.$path.''.$filename);
        }else{
            $imageUrl = asset($path.'thumb/'.$filename);
        }
        return $imageUrl;
    }

    public static function getUploadUrl($model,$id,$path){
        $imageUrl = 'public/dist/img/no-image.png';
        $linkPath = self::getConfigValue('link_path');
        $getUploadFileDetails = Upload::where('reference_type',$model)->where('reference_id',$id)->first();
        if(!empty($getUploadFileDetails)){
            $imageUrl = $linkPath.$path.''.$getUploadFileDetails->file;
        }
        return asset($imageUrl);
    }


    public static function removeUploadedImages($file_name,$path){
        $fileFullPath = self::getConfigValue('storage_path').$path;
        $filePath = storage_path($fileFullPath . $file_name);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public static function checkFileExists($file_name,$path){
        $fileFullPath = self::getConfigValue('storage_path').$path;
        $filePath = storage_path($fileFullPath . $file_name);
        if (file_exists($filePath)) {
            return true;
        }
    }

    public static function getConfigValue($key){
        $configValue = Config::get('constants.'.$key);
        if(!empty($configValue)){
            return $configValue;
        }else{
            return '';
        }
    }

    public static function shortString($string,$len = 50){
        $out = strlen($string) > $len ? mb_substr($string,0,$len)."..." : $string;
        return $out;
    }

    public static function getTableWiseData($model,$where = []){
        $activeStatus = self::getConfigValue('status.active');
        $getData =  $model::where('status',$activeStatus);
        if(!empty($where)){
            $getData = $getData->where($where);
        }
        return $getData->latest()->get();
    }

    public static function getUploadPath($folderName) : String {
        $linkPath = self::getConfigValue('link_path').$folderName;
        return $linkPath;
    }
}
<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;

class AdminDashboardService
{
    use CommonTrait;
    const module = 'Dashboard';

    public function index(){
        $activeStatus = CommonHelper::getConfigValue('status.active');
        $getClientCount = User::where('status',$activeStatus)->where('user_type',User::USERCLIENT)->count();
        $getEngineerCount = User::where('status',$activeStatus)->where('user_type',User::USERENGINEER)->count();
        $getProductCount = Product::where('status',$activeStatus)->count();
        $data['getTotalClient'] = (!empty($getClientCount)) ? $getClientCount : 0;
        $data['getTotalEngineer'] = (!empty($getEngineerCount)) ? $getEngineerCount : 0;
        $data['getTotalProducts'] = (!empty($getProductCount)) ? $getProductCount : 0;

        return $this->successResponseArr(self::module . __('messages.success.details'),$data);
    }
}
<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Entitymst;
use App\Models\Item;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;

class AdminDashboardService
{
    use CommonTrait;
    const module = 'Dashboard';

    public function index(){
        $activeStatus = CommonHelper::getConfigValue('status.active');
        $getClientCount = Entitymst::where('status',$activeStatus)->where('entity_type',Entitymst::ENTITYCLIENT)->count();
        $getEngineerCount = Entitymst::where('status',$activeStatus)->where('entity_type',Entitymst::ENTITYENGINEER)->count();
        $getItemCount = Item::where('status',$activeStatus)->count();
        $data['getTotalClient'] = (!empty($getClientCount)) ? $getClientCount : 0;
        $data['getTotalEngineer'] = (!empty($getEngineerCount)) ? $getEngineerCount : 0;
        $data['getTotalItems'] = (!empty($getItemCount)) ? $getItemCount : 0;

        return $this->successResponseArr(self::module . __('messages.success.details'),$data);
    }
}
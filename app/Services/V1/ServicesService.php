<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\ServiceCategoryResource;
use App\Helpers\CommonHelper;
use App\Models\Service;
use App\Models\User;
use App\Models\Upload;

class ServicesService
{
    use CommonTrait;
    const module = 'Service';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeStatus = CommonHelper::getConfigValue('status.active');
        $result = Service::with(['service_category'])
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) use ($activeStatus) {
                $query->where('status', $activeStatus);
            })
            ->latest('id')->get();
        return $this->successResponseArr(self::module . __('messages.success.list'), $result);
    }
}

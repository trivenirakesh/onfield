<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;
use App\Models\Address;
use App\Models\User;

class AddressService
{
    use CommonTrait;
    const module = 'Address';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeStatus = CommonHelper::getConfigValue('status.active');
        $response = Address::with([
            'address_type' => function ($query) {
                $query->select('id', 'name',);
            }, 'state'  => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) use ($activeStatus) {
                $query->where('user_id', auth()->id());
            })
            ->latest('id')->get();
        return $this->successResponseArr(self::module . __('messages.success.list'), $response);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validated();
        $input['user_id'] = auth()->user()->id;
        $input['created_by'] = auth()->user()->id;
        $input['created_ip'] = CommonHelper::getUserIp();
        $result = Address::create($input);
        return $this->successResponseArr(self::module . __('messages.success.create'), $result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Address::query()
            ->with([
                'address_type' => function ($query) {
                    $query->select('id', 'name',);
                }, 'state'  => function ($query) {
                    $query->select('id', 'name');
                }
            ])
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('id', $id)->first();
        if ($result == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        return $this->successResponseArr(self::module . __('messages.success.details'), $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $result = Address::where('id', $id)
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->first();
        if ($result == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $input = $request->validated();
        $input['updated_by'] = auth()->user()->id;
        $input['updated_ip'] = CommonHelper::getUserIp();

        $result->update($input);
        return $this->successResponseArr(self::module . __('messages.success.update'), $result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address =  Address::where('id', $id)
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->first();
        if ($address == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $address->deleted_by = auth()->user()->id;
        $address->deleted_ip = CommonHelper::getUserIp();
        $address->update();
        $result = $address->delete();
        if ($result) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}

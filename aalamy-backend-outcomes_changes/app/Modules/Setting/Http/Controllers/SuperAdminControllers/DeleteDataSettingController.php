<?php

namespace Modules\Setting\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\Setting\Http\DTO\DeleteDataSettingData;
use Modules\Setting\Http\Requests\DeleteDataSetting\UpdateDeleteDataSettingRequest;
use Modules\Setting\Http\Resources\DeleteDataSettingResource;
use Modules\Setting\Models\DeleteDataSetting;

class DeleteDataSettingController extends Controller
{

    public function show(){
        $deleteDataSetting = DeleteDataSetting::firstOrFail();
        return ApiResponseClass::successResponse(
            new DeleteDataSettingResource($deleteDataSetting)
        );

    }

    public function update(UpdateDeleteDataSettingRequest $request){
        $user = $request->user();
        $deleteDataSetting = DeleteDataSetting::firstOrFail();
        $deleteDataSettingData = DeleteDataSettingData::fromRequest($request);
        $deleteDataSetting->update(
            $deleteDataSettingData->initializeForUpdate($deleteDataSettingData)
        );
        return ApiResponseClass::successResponse(new DeleteDataSettingResource($deleteDataSetting));
    }


}

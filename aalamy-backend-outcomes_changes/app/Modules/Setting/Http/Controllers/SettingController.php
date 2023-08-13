<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\Setting\Http\DTO\DeleteDataSettingData;
use Modules\Setting\Http\DTO\SettingData;
use Modules\Setting\Http\Requests\DeleteDataSetting\UpdateDeleteDataSettingRequest;
use Modules\Setting\Http\Requests\Setting\UpdateLogoRequest;
use Modules\Setting\Http\Resources\DeleteDataSettingResource;
use Modules\Setting\Http\Resources\SettingResource;
use Modules\Setting\Models\DeleteDataSetting;
use Modules\Setting\Models\Setting;

class SettingController extends Controller
{

    public function getLogo(){
        $setting = Setting::firstOrFail();

        return ApiResponseClass::successResponse([
            'logo' => $setting->logo
        ]);
    }

}

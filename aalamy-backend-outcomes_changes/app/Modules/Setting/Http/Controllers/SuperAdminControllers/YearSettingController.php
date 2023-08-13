<?php

namespace Modules\Setting\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\Setting\Http\Requests\YearSetting\UpdateYearSettingRequest;
use Modules\Setting\Models\YearSetting;

class YearSettingController extends Controller
{


    public function get(){
        $yearSetting = YearSetting::firstOrFail();
        return ApiResponseClass::successResponse($yearSetting);
    }

    public function update(UpdateYearSettingRequest $request){
        $yearSetting = YearSetting::firstOrFail();
        $yearSetting->update([
           'start_date' => $request->start_date,
           'end_date' => $request->end_date,
        ]);
        return ApiResponseClass::successMsgResponse();
    }

}

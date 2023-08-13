<?php

namespace Modules\Setting\Http\Controllers\Classes;



use Carbon\Carbon;
use Modules\Setting\Models\DeleteDataSetting;

class SettingServices
{

    public static function checkDeleteDataExpireDate($deletedAt,DeleteDataSetting $deleteDataSetting){
        $carbonDeletedAt = Carbon::createFromFormat('Y-m-d H:i:s',$deletedAt);
//        $deleteDataSetting = DeleteDataSetting::firstOrFail();
        $timeClass = new TimeClass($carbonDeletedAt);
        $expireDate = $timeClass->getExpireDate($deleteDataSetting->time_for_force_delete_data,$deleteDataSetting->type);
        if($carbonDeletedAt > $expireDate)
            return true;
        return false;
    }


}

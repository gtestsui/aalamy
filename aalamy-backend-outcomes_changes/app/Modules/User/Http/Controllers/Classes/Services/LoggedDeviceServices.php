<?php


namespace Modules\User\Http\Controllers\Classes\Services;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\ServicesClass;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentInterface;
use App\Modules\User\Http\DTO\EducatorData;
use App\Modules\User\Http\DTO\ParentData;
use App\Modules\User\Http\DTO\SchoolData;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Mix;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\DTO\StudentData;
use Modules\Notification\Jobs\SendVerificationCodeNotification;
use Modules\User\Models\AccountConfirmationCodeSetting;
use Modules\User\Models\Educator;
use Modules\User\Models\ForgetPassword;
use Modules\User\Models\LoggedDevice;
use Modules\User\Models\ParentStudent;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class LoggedDeviceServices
{

    public static function checkLoggedDevicesCount($userId){
        $countLoggedDevices = LoggedDevice::where('user_id',$userId)->count();
        if($countLoggedDevices >= config('User.panel.logged_devices_count'))
            throw new ErrorMsgException(transMsg('huge_logged_devices_count',ApplicationModules::USER_MODULE_NAME));

    }

    public static function addLoggedDevice($userId,$deviceType,$deviceMac){
        $found = LoggedDevice::where('user_id', $userId)
            ->where('device_type' , $deviceType)
            ->where('device_mac' , $deviceMac)
            ->first();
        if(is_null($found))
            LoggedDevice::create([
                'user_id' => $userId,
                'device_type' => $deviceType,
                'device_mac' => $deviceMac,
            ]);
    }

    public static function deleteLoggedDevice($userId,$deviceType){
        LoggedDevice::where('user_id',$userId)->where('device_type',$deviceType)
            ->delete();
    }


}

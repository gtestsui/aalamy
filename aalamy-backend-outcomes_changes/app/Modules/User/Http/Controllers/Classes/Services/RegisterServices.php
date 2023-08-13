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

class RegisterServices
{

    public static function checkRegisteredAccount($credentials):User
    {

        if(!Auth::attempt($credentials))
            throw new ErrorMsgException(transMsg('wrong_credentials',ApplicationModules::USER_MODULE_NAME));

        $user = User::where(["email" => $credentials['email']])->first();
        return $user;
    }

    public static function checkRegisteredAccountByService($login_service_id,$email,$service):User
    {
        $user = User::where('login_service_id',$login_service_id)
            ->where('email',$email)
            ->where('account_id',$service)
            ->first();
        if(is_null($user))
            throw new ErrorMsgException(transMsg('wrong_credentials',ApplicationModules::USER_MODULE_NAME));
        return $user;
    }

    public static function checkAccountTypeForRegister($accountType){

        if(!in_array($accountType,config('User.panel.register_account_types')))
            throw new ErrorMsgException(transMsg('invalid_account_type',ApplicationModules::USER_MODULE_NAME));
        return $accountType;
    }

    /**
     * @param string $service
     */
    public static function checkServiceSupport($service){
        if(!in_array($service,config('User.panel.login_services')))
            throw new ErrorMsgException(transMsg('invalid_login_service',ApplicationModules::USER_MODULE_NAME));

    }

    public static function checkValidAccountType($accountType){

        if($accountType == 'teacher')
            return $accountType;

        //the last parameter in in_array because when passing first parameter 0 always return true
        if(!in_array($accountType,config('User.panel.all_account_types'),true))
            throw new ErrorMsgException(transMsg('invalid_account_type',ApplicationModules::USER_MODULE_NAME));
        return $accountType;
    }


}

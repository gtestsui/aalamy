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
use Modules\User\Observers\UserObserver;

class ConfirmationAccountServices
{

    public static function checkValidCode($storedCode,$requestedCode){
        if($storedCode != $requestedCode)
            throw new ErrorMsgException(transMsg('invalid_confirmation_code',ApplicationModules::USER_MODULE_NAME));

    }

    public static function checkCodeExpireDate($codeCreatedAt,$codeLongTime=null){
        if(is_null($codeLongTime))
            $codeLongTime = config('User.panel.account_confirmation_code_long_time',15);
        $transMsg = transMsg('invalid_confirmation_code_expiration_date',ApplicationModules::USER_MODULE_NAME);
        ServicesClass::checkCodeExpireDate($codeCreatedAt,$codeLongTime,$transMsg);
    }


    /**
     * @return string of numbers
     */
    public static function generateConfirmationCode():string
    {
       return '999999';
        $code = Self::generateRandomString(config('User.panel.confirmation_code_length'),'0123456789');
        return $code;
    }

    public static function generateParentCode():string
    {
        $code = Self::generateRandomString(6);
        $student = Student::where('parent_code',$code)->first();
        if(!is_null($student))
            return  Self::generateParentCode();
        return $code;
    }


    /**
     * we have observer working after update
     * @see UserObserver for send notification
     */
    public static function reSendConfirmationCode(User $user):User
    {
        /**
         * we used this because this filed are hidden in model
         */
        $user->verified_code = Self::generateConfirmationCode();
        $user->verified_code_created_at = Carbon::now();
        $user->save();
        return $user;
    }


    public static function generateRandomString($length = 10,$characters ='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'/*'!@#$%^&*_-'*/) {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @param User $user
     * @return User $user
     * if the account is verified before the just return
     * else update the status to verified and return
     */
    public static function confirmAccount(User $user):User
    {
        if($user->verified_status)
            return $user;

        $user->update([
            'verified_status' => 1,
            'email_verified_at' => Carbon::now()
        ]);
        return $user;
    }


}

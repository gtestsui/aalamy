<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ServicesClass;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Address\Http\Controllers\Classes\AddressServices;
use Modules\User\Http\DTO\UserData;
use Modules\User\Models\AccountConfirmationCodeSetting;
use Modules\User\Models\User;
use phpDocumentor\Reflection\Types\Void_;

class ConfirmationCodeClass
{

    private User $user;
    private $maxAttempts,$preventTimeInMinutes,$defaultAllowedDate;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->maxAttempts = config('User.panel.resend_account_confirmation_code_attempts');
        $this->preventTimeInMinutes = 2;
        $this->defaultAllowedDate = '1990/01/01 00:00:00';
    }

    /**
     * @return AccountConfirmationCodeSetting
     */
    public function getAccountConfirmationCodeSettingOrCreate(){
        $accountConfirmationCodeSetting =
            AccountConfirmationCodeSetting::where('user_id',$this->user->id)
            ->first();
        //initialize old date
        if(is_null($accountConfirmationCodeSetting))
            $accountConfirmationCodeSetting = AccountConfirmationCodeSetting::create([
                'user_id' => $this->user->id,
                'attempt_num' => 0,
                'allow_after_date' => $this->defaultAllowedDate,
            ]);
        return $accountConfirmationCodeSetting;
    }

    /**
     * @param string $timezone
     * @return void
     * @throws ErrorMsgException if the user has finished his attempts in resend
     * and the current dateTime in UTC smaller than allowedDate
     * to retry resendConfirmationCode and in UTC too
     */
    public function checkCanResendConfirmationCode($timezone){
        $accountConfirmationCodeSetting = $this->getAccountConfirmationCodeSettingOrCreate();
        $allowedDate = $accountConfirmationCodeSetting->allow_after_date;
        //transfer current time from default timezone to utc timezone
        $now = ServicesClass::toTimezone(
            Carbon::now(),config('app.timezone'),'UTC'
        );

        if($accountConfirmationCodeSetting->attempt_num >= $this->maxAttempts
            && $allowedDate>$now){
            //$allowedDate is default in utc so we want to change it to
            // client timezone to display the correct time for him

            $allowedDate = ServicesClass::toTimezone(
                $allowedDate,'UTC',$timezone
            );

            throw new ErrorMsgException(transMsg('confirmation_code_allowed_date','User',['dateTime'=>$allowedDate]));
        }

        $this->increaseResendConfirmationAccountAttempts($accountConfirmationCodeSetting);

    }

    /**
     * @param AccountConfirmationCodeSetting $accountConfirmationCodeSetting
     * @return void
     * increase the attempts of trying resendConfirmationCode
     * and if the attempts num after increase bigger than possible attempts num
     * the user should wait 2 minutes to get another attempt
     */
    public function increaseResendConfirmationAccountAttempts(AccountConfirmationCodeSetting $accountConfirmationCodeSetting){
        $accountConfirmationCodeSetting->attempt_num += 1;

        if($accountConfirmationCodeSetting->attempt_num >= $this->maxAttempts)
            $accountConfirmationCodeSetting->allow_after_date =
                Carbon::now()->addMinutes($this->preventTimeInMinutes);

        $accountConfirmationCodeSetting->save();

    }


    /**
     * after the user confirm his account so we reset the attempts num
     */
    public function resetAttempts(){
        $accountConfirmationCodeSetting = $this->getAccountConfirmationCodeSettingOrCreate();
        $accountConfirmationCodeSetting->attempt_num = 0;
        $accountConfirmationCodeSetting->allow_after_date = $this->defaultAllowedDate;
        $accountConfirmationCodeSetting->save();

    }


}

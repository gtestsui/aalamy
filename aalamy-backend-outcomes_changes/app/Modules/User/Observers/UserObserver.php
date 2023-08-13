<?php

namespace Modules\User\Observers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ServicesClass;
use Carbon\Carbon;
use Modules\Notification\Jobs\SendVerificationCodeNotification;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class UserObserver
{


    /**
     * Handle the Product "created" event.
     *
     * @param  \Modules\User\Models\User  $product
     * @return void
     */
    public function creating(User $user)
    {
        if(in_array($user->account_id,config('User.panel.login_services')))
            $user->verified_status = 1;
        else{
//            $user->verified_code = UserServices::generateConfirmationCode();
            $user->verified_code = ConfirmationAccountServices::generateConfirmationCode();
            $user->verified_code_created_at = Carbon::now();
        }

    }

    /**
     * Handle the User "created" event.
     *
     * @param  \Modules\User\Models\User  $user
     * @return void
     * check if the user logged in by outer service or register normal way
     */
    public function created(User $user)
    {
            if(!in_array($user->account_id,config('User.panel.login_services'))
                && $user->account_type !=config('User.panel.all_account_types.superAdmin'))
                    ServicesClass::dispatchJob(new SendVerificationCodeNotification($user,$user->verified_code));

    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \Modules\User\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {

        if($user->wasChanged('verified_code')){
            // verified_code has changed
            ServicesClass::dispatchJob(new SendVerificationCodeNotification($user,$user->verified_code));
        }

        /*if($user->isDeletedAsSoft()){

            $this->deleted($user);
        }

        if($user->isRestored()){

            $this->restored($user);
        }*/

    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \Modules\User\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $user->cascadeSoftDelete();
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \Modules\User\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        $user->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \Modules\User\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}

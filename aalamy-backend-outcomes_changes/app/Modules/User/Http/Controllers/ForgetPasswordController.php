<?php

namespace Modules\User\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Controllers\Classes\ConfirmationCodeClass;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Requests\ForgetPassword\ChangePasswordRequest;
use Modules\User\Http\Requests\ForgetPassword\CheckForgetPasswordRequest;
use Modules\User\Http\Requests\ForgetPassword\ForgetPasswordRequest;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request){
        $user = User::where('email',$request->email)
            ->registeredBy('email')
            ->first();
        if(is_null($user))
            throw new ErrorMsgException(transMsg('forget_password_invalid_email',ApplicationModules::USER_MODULE_NAME));

        $confirmationCodeClass = new ConfirmationCodeClass($user);
        $confirmationCodeClass->checkCanResendConfirmationCode($request->my_timezone);
//        $user = UserServices::reSendConfirmationCode($user);
        $user = ConfirmationAccountServices::reSendConfirmationCode($user);

        return ApiResponseClass::successMsgResponse();

    }

    public function checkForgetPasswordCode(CheckForgetPasswordRequest $request){
        DB::beginTransaction();

        $user = User::where('email',$request->email)
            ->first();
//        UserServices::checkValidCode($user->verified_code, $request->account_confirmation_code);
        ConfirmationAccountServices::checkValidCode(
            $user->verified_code, $request->account_confirmation_code
        );
//        UserServices::checkCodeExpireDate($user->verified_code_created_at);
        ConfirmationAccountServices::checkCodeExpireDate(
            $user->verified_code_created_at
        );

        $confirmationCodeClass = new ConfirmationCodeClass($user);
        $confirmationCodeClass->resetAttempts();

        $user[config('User.panel.auth_token_name')] = $user->createToken('token')->accessToken;
        DB::commit();
        return ApiResponseClass::successResponse(new UserResource($user));

    }


    /**
     * after user do forget password
     * he can change his password directly
     */
    public function changePassword(ChangePasswordRequest $request){
        $user = $request->user();
        $user->password = $request->password;
        $user->save();
        return ApiResponseClass::successResponse(new UserResource($user));
    }


}

<?php

namespace Modules\User\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\AccountDetails\AccountDetailsFactory;
use Modules\User\Http\Controllers\Classes\AccountProfile\AccountProfileFactory;
use Modules\User\Http\Controllers\Classes\AccountProfile\StudentProfile;
use Modules\User\Http\Controllers\Classes\ConfirmationCodeClass;
use Modules\User\Http\Controllers\Classes\FirebaseServices;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Controllers\Classes\Services\LoggedDeviceServices;
use Modules\User\Http\Controllers\Classes\UserClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\DTO\AuthData;
use Modules\User\Http\DTO\UserData;
use Modules\User\Http\Requests\ConfirmAccountRequest;
use Modules\User\Http\Requests\ForgetPassword\ChangePasswordRequest;
use Modules\User\Http\Requests\Login\LoginUserRequest;
use Modules\User\Http\Requests\LogoutUserRequest;
use Modules\User\Http\Requests\Register\RegisterUserRequest;
use Modules\User\Http\Requests\UpdateClientLangRequest;
use Modules\User\Http\Requests\UserProfile\UpdatePasswordRequest;
use Modules\User\Http\Requests\UserProfile\UpdateUserAccountRequest;
use Modules\User\Http\Requests\UserProfile\UpdateUserPersonalInfoRequest;
use Modules\User\Http\Requests\UserProfile\UpdateUserPersonalWithAccountRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\AccountConfirmationCodeSetting;
use Modules\User\Models\User;

class UserController extends Controller
{


    public function login(LoginUserRequest $request){
        $userClass = new UserClass();
        $authData = AuthData::fromRequest($request);
        $user = $userClass->login($authData);
        return ApiResponseClass::successResponse((new UserResource($user,$user->account_type)));
    }

    /**
     * after registering we use observer to send verification code on mail
     */
    public function register(RegisterUserRequest $request,$accountType){
        try{
            DB::beginTransaction();
            $userClass = new UserClass();
            $user = $userClass->register($request,$accountType);
            DB::commit();
            return ApiResponseClass::successResponse((new UserResource($user,$user->account_type)));
        }catch(\Exception $e){
            DB::rollBack();
            return ApiResponseClass::errorMsgResponse($e->getMessage());
        }

    }

    public function updateAccountWithPersonalInfo(UpdateUserPersonalWithAccountRequest $request){
        $user = $request->user();
        $accountType = $user->account_type;
        DB::beginTransaction();
        $userData = UserData::fromRequest($request,$accountType);
        $childOfUserClassByType = UserServices::getObjectFromUserClassChildByType($accountType);
        $requestDataByType = $childOfUserClassByType->getDataFromRequest($request,$userData);
        $user = $childOfUserClassByType->updateAccountWithPersonalInfo($requestDataByType,$userData,$user);
        $user->my_token = explode(' ',$request->header('Authorization'))[1];
        DB::commit();
        return ApiResponseClass::successResponse((new UserResource($user,$user->account_type)));

    }

    public function updatePersonalInfo(UpdateUserPersonalInfoRequest $request){
        $user = $request->user();
        $accountType = $user->account_type;
        DB::beginTransaction();
        $userData = UserData::fromRequest($request,$accountType);
        $userClass = new UserClass();
        $user = $userClass->updateUser($userData,$user);
        DB::commit();
        return ApiResponseClass::successResponse((new UserResource($user,$user->account_type)));


    }

    /**
     * update the data in tables Educator or school or ...
     * @param UpdateUserAccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ErrorMsgException
     */
    public function updateAccountInfo(UpdateUserAccountRequest $request){
        $user = $request->user();
        $accountType = $user->account_type;
        DB::beginTransaction();

        $childOfUserClassByType = UserServices::getObjectFromUserClassChildByType($accountType);
        $requestDataByType = $childOfUserClassByType->getDataFromRequest($request);
        $user = $childOfUserClassByType->update($requestDataByType,$user);
        DB::commit();
        return ApiResponseClass::successResponse((new UserResource($user,$user->account_type)));


    }

    public function confirmAccount(ConfirmAccountRequest $request){
        $user = $request->user();
//        UserServices::checkValidCode($user->verified_code, $request->account_confirmation_code);
        ConfirmationAccountServices::checkValidCode(
            $user->verified_code, $request->account_confirmation_code
        );
//        UserServices::checkCodeExpireDate($user->verified_code_created_at);
        ConfirmationAccountServices::checkCodeExpireDate(
            $user->verified_code_created_at
        );
//        $user = UserServices::confirmAccount($user);
        $user = ConfirmationAccountServices::confirmAccount($user);
        $confirmationCodeClass = new ConfirmationCodeClass($user);
        $confirmationCodeClass->resetAttempts();
        return ApiResponseClass::successMsgResponse();

    }

    public function getAllowedResendDate(Request $request){
        $user = $request->user();
        $confirmationCodeClass = new ConfirmationCodeClass($user);
        $accountConfirmationCodeSetting = $confirmationCodeClass->getAccountConfirmationCodeSettingOrCreate();
        return ApiResponseClass::successResponse($accountConfirmationCodeSetting);
    }

    public function resendConfirmationAccountCode(Request $request){
        $user = $request->user();
        DB::beginTransaction();
        $confirmationCodeClass = new ConfirmationCodeClass($user);
        $confirmationCodeClass->checkCanResendConfirmationCode($request->my_timezone);
//        $user = UserServices::reSendConfirmationCode($user);
        $user = ConfirmationAccountServices::reSendConfirmationCode($user);
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }


    public function myInfo(Request $request){
        $user = $request->user();
        $user->load(ucfirst($user->account_type));
        return ApiResponseClass::successResponse(new UserResource($user));
    }

    /**
     * when the user update password he needs to insert his old pass too
     */
    public function updatePassword(UpdatePasswordRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $userClass = new UserClass();
        $user = $userClass->updatePassword($user,$request->old_password,$request->new_password);
        $newToken = UserServices::revokeAllTokens($user);
        $user[config('User.panel.auth_token_name')] = $newToken;

        DB::commit();
        return ApiResponseClass::successResponse(new UserResource($user));
    }

    public function getProfile(Request $request,$user_id){
        $user = User::findOrFail($user_id);
        if(UserServices::isStudent($user)){
            $user->load([
                'Student' => function($query){
                    return $query->with([
                        'BasicInformation',
                        'FamilyInformation',
                        'OtherInformation',
                        'SocialAndPersonalInformation',
                    ]);
                },
            ]);
        }
        else{
            $user->load(ucfirst($user->account_type));
        }
        $accountClass = AccountProfileFactory::create($user,$request->my_teacher_id);
        return(ApiResponseClass::successResponse($accountClass->profile()));
    }

    public function updateClientLang(UpdateClientLangRequest $request){
        $user = $request->user();
        FirebaseServices::updateLang($user,$request->firebase_token,$request->new_lang);
        return ApiResponseClass::successMsgResponse();

    }

    public function getDetails(Request $request){
        $user = $request->user();
        $accountDetails = AccountDetailsFactory::create($user);
        $details = $accountDetails->getDetails();

        return ApiResponseClass::successResponse($details);

    }

    public function logout(LogoutUserRequest $request)
    {
        $user = $request->user();
//        UserServices::deleteLoggedDevice($user->id,$request->device_type);
        LoggedDeviceServices::deleteLoggedDevice($user->id,$request->device_type);
        FirebaseServices::deleteFireBaseToken($user,$request->firebase_token);
        $user->token()->revoke();
        return ApiResponseClass::successMsgResponse();
    }



}

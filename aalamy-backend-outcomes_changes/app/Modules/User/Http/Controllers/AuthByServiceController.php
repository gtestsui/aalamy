<?php

namespace Modules\User\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\User\Http\Controllers\Classes\FirebaseServices;
use Modules\User\Http\Controllers\Classes\UserClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\DTO\AuthData;
use Modules\User\Http\DTO\UserData;
use Modules\User\Http\Requests\Login\LoginUserByServiceRequest;
use Modules\User\Http\Requests\Register\RegisterUserByServiceRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthByServiceController extends Controller
{

    /*public function redirectToService(Request $request,$service){
        UserServices::checkServiceSupport($service);
        return Socialite::driver($service)->redirect();
    }

    public function serviceCallback(Request $request,$service){
        UserServices::checkServiceSupport($service);
        $socialiteUser = Socialite::driver($service)->user();
        $userFromData = User::where('login_service_id',$socialiteUser->getId())
            ->where('account_id',$service)
            ->first();

        if(is_null($userFromData)){
            $frontUrl = UserServices::initializeRegisterUrlInFront($socialiteUser);
        }else{
            $frontUrl = UserServices::initializeLoginByServiceUrlInFront($userFromData);
        }

        redirect()->to($frontUrl);
    }*/

    /*public function register(RegisterUserByServiceRequest $request,$accountType,$service){
        UserServices::checkServiceSupport($service);
        try{
            DB::beginTransaction();
            $userData = UserData::fromRequest($request,$accountType,$service);
            $user = User::create($userData->all());
            //create account depends on the accountType
            $account = UserServices::{'create'.ucfirst($accountType)}($request,$user);
            $user = User::with(ucfirst($accountType))->find($user->id);
            $user['token'] = $user->createToken('token')->accessToken;
            DB::commit();
            UserServices::addLoggedDevice($user->id,$userData->device_type);
            return ApiResponseClass::successResponse($user);
        }catch(\Exception $e){
            DB::rollBack();
            return ApiResponseClass::errorMsgResponse($e->getMessage());
        }

    }*/

    public function login(LoginUserByServiceRequest $request,$service){
        $userClass = new UserClass();
        $authData = AuthData::fromRequest($request);
        $user = $userClass->login($authData,$service);
        return ApiResponseClass::successResponse((new UserResource($user,$user->account_type)));


    }

    public function register(RegisterUserByServiceRequest $request,$accountType,$service){
        try{
            DB::beginTransaction();
            $userClass = new UserClass();
            $user = $userClass->register($request,$accountType,$service);
             DB::commit();
            return ApiResponseClass::successResponse((new UserResource($user,$user->account_type)));

        }catch(\Exception $e){
            DB::rollBack();
            return ApiResponseClass::errorMsgResponse($e->getMessage());
        }

    }


}

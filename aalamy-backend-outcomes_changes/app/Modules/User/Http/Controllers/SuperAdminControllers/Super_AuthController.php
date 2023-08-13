<?php

namespace Modules\User\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Controllers\Classes\FirebaseServices;
use Modules\User\Http\Controllers\Classes\Services\RegisterServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\Requests\Login\LoginSuperAdminRequest;
use Modules\User\Http\Requests\LogoutSuperAdminRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;

class Super_AuthController extends Controller
{



    public function login(LoginSuperAdminRequest $request){
        $remember_me  = $request->remember_me ? $request->remember_me : false;

        $credentials = request(['email', 'password']);
//        $user = UserServices::checkRegisteredAccount($credentials);
        $user = RegisterServices::checkRegisteredAccount($credentials);

        UserServices::checkRoles($user,['superAdmin']);
        Auth::login($user, $remember_me);
        $user[config('User.panel.auth_token_name')] = $user->createToken('token')->accessToken;
        FirebaseServices::saveFirebaseToken($user,$request->firebase_token,$request->lang);
        return ApiResponseClass::successResponse((new UserResource($user,$user->account_type)));

    }


    public function logout(LogoutSuperAdminRequest $request)
    {
        $user = $request->user();
        FirebaseServices::deleteFireBaseToken($user,$request->firebase_token);
        $user->token()->revoke();
        return ApiResponseClass::successMsgResponse();
    }

}

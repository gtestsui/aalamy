<?php

namespace Modules\User\Http\Requests\Login;

use App\Http\Controllers\Classes\ApiResponseClass;
use Modules\User\Http\Controllers\Classes\Services\RegisterServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\User\Models\User;

class LoginUserByServiceRequest extends BaseLoginRequest
{
    public function rules()
    {
//        UserServices::checkServiceSupport($this->route('service'));
        RegisterServices::checkServiceSupport($this->route('service'));

        $parentRules = Parent::rules();
        $myRules = [
//            'login_service_id' => 'required|exists:users,login_service_id',
//            'login_service_id' => 'required|exists:'.(new User())->getTable().',login_service_id',
            'login_service_id' => 'required',
            'email' => 'nullable',
            'service_access_token' => 'required',

        ];

        return array_merge($myRules,$parentRules);

    }



}

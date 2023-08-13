<?php

namespace Modules\User\Http\Requests\Login;

use App\Http\Controllers\Classes\ApiResponseClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\User\Traits\ValidationAttributesTrans;

class LoginUserRequest extends BaseLoginRequest
{

    use ValidationAttributesTrans;

    public function rules()
    {
        $parentRules = Parent::rules();
        $myRules = [
          'email' => 'required|email',
          'password' => 'required',
        ];

        return array_merge($myRules,$parentRules);
    }




}

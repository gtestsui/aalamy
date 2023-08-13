<?php

namespace Modules\User\Http\Requests\Login;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class BaseLoginRequest extends FormRequest
{
    use ResponseValidationFormRequest;


    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
          'device_type' => ['required',Rule::in(config('User.panel.user_device_types'))],
          'device_mac' => 'nullable',
          'firebase_token' => 'required'
        ];
    }




}

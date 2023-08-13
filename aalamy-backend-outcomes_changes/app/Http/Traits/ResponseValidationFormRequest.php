<?php


namespace App\Http\Traits;


use App\Http\Controllers\Classes\ApiResponseClass;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ResponseValidationFormRequest
{

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            ApiResponseClass::validateResponse($validator->errors())
        );
    }

}

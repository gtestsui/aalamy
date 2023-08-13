<?php

namespace App\Exceptions;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ApplicationModules;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class UnVerifiedAccountException extends Exception
{
    protected $message;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->message = transMsg('not_verified_account',ApplicationModules::USER_MODULE_NAME);

        parent::__construct($message, $code, $previous);
    }

//    public function getErrorAsJson(){
//        return ApiResponseClass::errorMsgResponse($this->message);
//    }

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        Log::channel('customized_logger')->error(json_encode([
            'message' => $this->message,
        ]));

    }

    public function render($request)
    {

        if(!empty($this->message))
            return ApiResponseClass::errorMsgResponse($this->message,406);
        return ApiResponseClass::errorMsgResponse('your account isn\'t verified',406);
    }
}

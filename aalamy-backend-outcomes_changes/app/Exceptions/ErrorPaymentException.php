<?php

namespace App\Exceptions;

use App\Http\Controllers\Classes\ApiResponseClass;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorPaymentException extends Exception
{
    protected $message;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->message = $message;

        parent::__construct($message, $code, $previous);
    }

//    public function getErrorAsJson(){
//        return ApiResponseClass::errorMsgResponse($this->message);
//    }

    public function render($request)
    {
        Log::channel('customized_logger')->error(json_encode([
            'message' => 'failed payment',
        ]));
//        if(empty($this->message))
            return view('SubscriptionPlan::failed-payment-status');
    }
}

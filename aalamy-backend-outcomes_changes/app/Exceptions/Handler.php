<?php

namespace App\Exceptions;

use App\Http\Controllers\Classes\ApiResponseClass;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, \Throwable $exception)
    {

        if ($exception instanceof ModelNotFoundException)
        {

            //Modules\ModuleName\Models\ModelName
            $modelPath = explode('\\',$exception->getModel());

            if(isset($modelPath[3]))
                return ApiResponseClass::notFoundResponse($modelPath[3].transMsg('invalid_id'));
            else
                return ApiResponseClass::notFoundResponse(transMsg('invalid_id'));

        }


//        if($exception instanceof ErrorMsgException) {
//            return $exception->getErrorAsJson();
//        }

        $response = parent::render($request, $exception);
        $errorData = $response->getData();
        if(!$exception instanceof ErrorUnAuthorizationException &&
            !$exception instanceof ErrorMsgException &&
            !$exception instanceof ErrorPaymentException
        ){
            Log::channel('customized_logger')->error(json_encode([
                'message' => $errorData->message,
                'file' => $errorData->file??null,
                'line' => $errorData->line??null,
            ]));
        }

        return  $response;
    }
}

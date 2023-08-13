<?php


namespace App\Http\Controllers\Classes;


use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    public static function successResponse($data){
        Log::channel('customized_logger')->debug('endpoint_response_status: success');
        Log::channel('customized_logger')->debug('returned_data : '.json_encode($data));
        Log::channel('customized_logger')->info('end_request');
        Log::channel('customized_logger')->info('//////////////////////////');

        return response()->json([
            'message'=>trans('messages.successfully'),
            'success'=>'true',
            'status_code'=>200,
            'data'=>$data],
            200);
    }

    public static function validateResponse($errors){
        Log::channel('customized_logger')->debug('endpoint_response_status: validate error');
        Log::channel('customized_logger')->debug('errors : '.json_encode($errors));
        Log::channel('customized_logger')->info('end_request');
        Log::channel('customized_logger')->info('//////////////////////////');

        return response()->json([
            'message'=>trans('messages.validation_error'),
            'success'=>'false',
            'status_code'=>422,
            'errors'=>$errors->all()],
            422);

    }

    public static function deletedResponse($msg=null){
        Log::channel('customized_logger')->debug('endpoint_response_status: success');
        Log::channel('customized_logger')->info('end_request');
        Log::channel('customized_logger')->info('//////////////////////////');

        if(is_null($msg)) $msg = trans('messages.deleted');
        return response()->json([
            'message'=>$msg,
            'success'=>'true',
            'status_code'=>200,
            'data'=>[]],
            200);
    }


    public static function successMsgResponse($msg=null){
        Log::channel('customized_logger')->debug('endpoint_response_status: success');
        Log::channel('customized_logger')->info('end_request');
        Log::channel('customized_logger')->info('//////////////////////////');

        if(is_null($msg)) $msg = trans('messages.successfully');
        return response()->json([
            'message'=>$msg,
            'success'=>'true',
            'status_code'=>200,
            'data'=>[]],
            200);
    }

    public static function notFoundResponse($msg=null){
        Log::channel('customized_logger')->debug('endpoint_response_status: not found error');
        Log::channel('customized_logger')->info('end_request');
        Log::channel('customized_logger')->info('//////////////////////////');

        if(is_null($msg)) $msg = trans('messages.not_found');

        return response()->json([
            'message'=>$msg,
            'success'=>'false',
            'status_code'=>404,
            'errors'=>[$msg]],
            404);
    }


    public static function unauthorizedResponse(){
        Log::channel('customized_logger')->debug('endpoint_response_status:  unauthorized error');
        Log::channel('customized_logger')->info('end_request');
        Log::channel('customized_logger')->info('//////////////////////////');

        return response()->json([
            'message'=>trans('messages.unAuthorized'),
            'success'=>'false',
            'status_code'=>401,
            'errors'=>[trans('messages.unAuthorized')]],
            401);
    }

    public static function errorMsgResponse($msg=null,$code=400){
        Log::channel('customized_logger')->debug('endpoint_response_status: error msg');

        if(is_null($msg)) $msg = trans('messages.something_went_wrong');

        Log::channel('customized_logger')->debug('msg : '.$msg);
        Log::channel('customized_logger')->info('end_request');
        Log::channel('customized_logger')->info('//////////////////////////');

        if(is_array($msg)) $msgArray = $msg;
        else  $msgArray = [$msg];
        return response()->json([
            'message'=>$msg,
            'success'=>'false',
            'status_code'=>$code,
            'errors'=>$msgArray],
            $code);
    }






}

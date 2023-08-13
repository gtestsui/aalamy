<?php


namespace App\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class RequestServicesClass
{


    /**
     * we will use this function to get ids that passed in query
     * (if we want to encrypt them from front then here the place
     *  where we should decrypt)
     *
     */
    public static function getId($id){
        return $id;
    }

    /**
     * we will use this function to get ids that passed in query
     * (if we want to encrypt them from front then here the place
     *  where we should decrypt)
     *
     */
    public static function getParam($param){
        return $param;
    }

    /**
     * we will use this function to get user token that passed in query
     * (if we want to encrypt them from front then here the place
     *  where we should decrypt)
     *
     */
    public static function getQueryToken($token){
        return $token   ;
    }


}

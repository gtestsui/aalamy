<?php


namespace App\Http\Controllers\DTO\Parents;
use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ServicesClass;
use \Spatie\DataTransferObject\DataTransferObject;

use Carbon\Carbon;

class ObjectData extends DataTransferObject
{

//    public static function generateCarbonObject(?string $date,$dateFormatWithTime=false): ?Carbon
    public static function generateCarbonObject(?string $date,$ignoreTimeZone=false): ?Carbon

    {

        if (!$date) {

            return null;

        }

        if(is_null(request()->time_zone) || $ignoreTimeZone){
            return new Carbon($date);
        }

        return ServicesClass::toTimezone($date,request()->time_zone,config('panel.timezone'));

//        if(is_null(request()->time_zone)){
//
//            if($dateFormatWithTime)
//                return Carbon::createFromFormat(
//                    config('panel.standard_date_time_format'),$date
//                );
//
//            return Carbon::createFromFormat(
//                config('panel.date_format'),$date
//            );
////            return new Carbon($date);
//        }
//
//        return ServicesClass::toTimezone($date,request()->time_zone,config('panel.timezone'));



    }

    public static function generateCarbonTimeObject(?string $date): ?Carbon

    {

        if (!$date) {

            return null;

        }

        return Carbon::createFromFormat(
            config('panel.time_format'),$date
        );

    }

    /**
     * @param array-key of strings
     * @return static
     * @throws  ErrorMsgException
     */
    public function merge(array $fields){
        foreach ($fields as $fieldName =>$field){
            if($this->checkPropertyExists($fieldName)){
                $this->{$fieldName} = $field;
            }
        }
        return $this;
    }

    /**
     * check if the property @param string $name is exists in the class variables
     * else @throws ErrorMsgException
     */
    private function checkPropertyExists($name){
        if(!property_exists($this, $name)){
            throw new ErrorMsgException(
                'you trying to sign to field doesnt exist in the '.get_class($this)
            );
        }
        return true;
    }

    public function initializeForUpdate(?ObjectData $data=null){

        $arrayUpdate = [];
        foreach ($this->all() as $key=>$element){
            if(isset($element))
                $arrayUpdate[$key]=$element;
        }
        return $arrayUpdate;


//        $arrayUpdate = [];
//        foreach ($data->all() as $key=>$element){
//            if(isset($element))
//                $arrayUpdate[$key]=$element;
//        }
//        return $arrayUpdate;
    }

}

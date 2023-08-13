<?php

namespace Modules\Notification\Traits;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait SetTimeZone
{
    public $tz = 'UTC';

    public function getCreatedAtAttribute($value){
        try {
            return (new Carbon($value))->setTimezone(new CarbonTimeZone($this->tz));
        } catch (\Exception $e) {
            return 'Invalid DateTime Exception: '.$e->getMessage();
        }
    }

//    public function getUpdatedAtAttribute($value){
//        try {
//            return (new Carbon($value))->setTimezone(new CarbonTimeZone($this->tz));
//        } catch (\Exception $e) {
//            return 'Invalid DateTime Exception: '.$e->getMessage();
//        }
//    }
}

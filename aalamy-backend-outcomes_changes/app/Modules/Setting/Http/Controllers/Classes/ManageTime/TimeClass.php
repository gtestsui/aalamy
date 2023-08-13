<?php

namespace Modules\Setting\Http\Controllers\Classes;



use Carbon\Carbon;

class TimeClass
{

    private Carbon $date;
    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    public function getExpireDate($numberOf,$type/*,Carbon $date*/){
        return $this->date->{'add'.$type}($numberOf);
    }




}

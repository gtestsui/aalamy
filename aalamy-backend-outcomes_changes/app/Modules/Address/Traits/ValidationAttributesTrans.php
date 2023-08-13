<?php

namespace Modules\Address\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::ADDRESS_MODULE_NAME;
    public function attributes(){
        return [
            //register
            'name_en' => transValidationParameter('name_en',$this->moduleName),
            'name_ar' => transValidationParameter('name_ar',$this->moduleName),


        ];
    }
}

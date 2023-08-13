<?php

namespace Modules\Level\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::LEVEL_MODULE_NAME;
    public function attributes(){
        return [
            'name' => transValidationParameter('name',$this->moduleName),
            'level_id' => transValidationParameter('level_id',$this->moduleName),
            'subject_id' => transValidationParameter('subject_id',$this->moduleName),
        ];
    }
}

<?php

namespace Modules\Meeting\Traits;

use App\Http\Controllers\Classes\ApplicationModules;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::MEETING_MODULE_NAME;
    public function attributes(){
        return [
            'name' => transValidationParameter('name',$this->moduleName),
            'date' => transValidationParameter('date',$this->moduleName),
        ];
    }
}

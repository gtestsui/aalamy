<?php

namespace Modules\Setting\Traits;

use App\Http\Controllers\Classes\ApplicationModules;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::SETTING_MODULE_NAME;
    public function attributes(){
        return [
            'time_for_force_delete_data' => transValidationParameter('time_for_force_delete_data',$this->moduleName),
            'type' => transValidationParameter('type',$this->moduleName),
        ];
    }
}

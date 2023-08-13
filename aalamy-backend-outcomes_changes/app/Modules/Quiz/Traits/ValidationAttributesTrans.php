<?php

namespace Modules\Sticker\Traits;

use App\Http\Controllers\Classes\ApplicationModules;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::QUIZ_MODULE_NAME;
    public function attributes(){
        return [
            'name' => transValidationParameter('name',$this->moduleName),
            'date' => transValidationParameter('date',$this->moduleName),
        ];
    }
}

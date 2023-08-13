<?php

namespace Modules\HelpCenter\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::HELP_CENTER_MODULE_NAME;
    public function attributes(){
        return [
            'name' => transValidationParameter('name',$this->moduleName),
            'description' => transValidationParameter('description',$this->moduleName),
            'image' => transValidationParameter('image',$this->moduleName),
        ];
    }
}

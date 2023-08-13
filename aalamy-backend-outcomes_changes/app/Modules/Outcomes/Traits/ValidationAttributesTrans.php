<?php

namespace Modules\Level\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::OUTCOMES_MODULE_NAME;
    public function attributes(){
        return [
            'name' => transValidationParameter('name',$this->moduleName),

        ];
    }
}

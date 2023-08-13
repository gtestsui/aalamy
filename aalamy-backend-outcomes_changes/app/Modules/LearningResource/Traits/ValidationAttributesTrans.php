<?php

namespace Modules\LearningResource\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::DISCUSSION_CORNER_MODULE_NAME;
    public function attributes(){
        return [
            'text' => transValidationParameter('text',$this->moduleName),
            'priority' => transValidationParameter('priority',$this->moduleName),
            'pictures.*' => transValidationParameter('pictures.*',$this->moduleName),
            'files.*' => transValidationParameter('files.*',$this->moduleName),
        ];
    }
}

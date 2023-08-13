<?php

namespace Modules\StudentAchievement\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::STUDENT_ACHIEVEMENT_MODULE_NAME;
    public function attributes(){
        return [
            'title' => transValidationParameter('title',$this->moduleName),
            'description' => transValidationParameter('description',$this->moduleName),
            'file' => transValidationParameter('file',$this->moduleName),

        ];
    }
}

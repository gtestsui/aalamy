<?php

namespace Modules\QuestionBank\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::QUESTION_BANK_MODULE_NAME;
    public function attributes(){
        return [
            'name' => transValidationParameter('name',$this->moduleName),
            'level_id' => transValidationParameter('level_id',$this->moduleName),
        ];
    }
}

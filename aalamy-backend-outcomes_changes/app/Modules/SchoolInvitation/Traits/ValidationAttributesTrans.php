<?php

namespace Modules\SchoolInvitation\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::SCHOOL_INVITATION_MODULE_NAME;
    public function attributes(){
        return [
            //register
            'introductory_message' => transValidationParameter('introductory_message',$this->moduleName),
            'reject_cause' => transValidationParameter('reject_cause',$this->moduleName),
            'teacher_email' => transValidationParameter('teacher_email',$this->moduleName),


        ];
    }
}

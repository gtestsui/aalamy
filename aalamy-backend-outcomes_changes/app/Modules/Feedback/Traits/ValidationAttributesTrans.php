<?php

namespace Modules\Feedback\Traits;

use App\Http\Controllers\Classes\ApplicationModules;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::FEEDBACK_MODULE_NAME;
    public function attributes(){
        return [
            'student_id' => transValidationParameter('student_id',$this->moduleName),
            'text' => transValidationParameter('text',$this->moduleName),
            'from_date' => transValidationParameter('from_date',$this->moduleName),
            'to_date' => transValidationParameter('to_date',$this->moduleName),
            'share_with_parent' => transValidationParameter('share_with_parent',$this->moduleName),
            'file' => transValidationParameter('file',$this->moduleName),
            'image' => transValidationParameter('image',$this->moduleName),
        ];
    }
}

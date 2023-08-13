<?php

namespace Modules\EducatorStudentRequest\Traits;

use App\Http\Controllers\Classes\ApplicationModules;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::EDUCATOR_STUDENT_REQUEST_MODULE_NAME;
    public function attributes(){
        return [
            'name' => transValidationParameter('name',$this->moduleName),
            'description' => transValidationParameter('description',$this->moduleName),
            'color' => transValidationParameter('color',$this->moduleName),
        ];
    }
}

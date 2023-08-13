<?php

namespace Modules\ContactUs\Traits;

use App\Http\Controllers\Classes\ApplicationModules;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::CONTACT_US_MODULE_NAME;
    public function attributes(){
        return [
            'subject' => transValidationParameter('subject',$this->moduleName),
            'text' => transValidationParameter('text',$this->moduleName),
        ];
    }
}

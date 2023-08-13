<?php

namespace Modules\User\Traits;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\User\Http\Controllers\Classes\ImportStudentClasses\FileServices;

trait ValidationAttributesTrans
{
    private $moduleName = ApplicationModules::USER_MODULE_NAME;
    public function attributes(){
        return [
            //register
            'fname' => transValidationParameter('fname',$this->moduleName),
            'lname' => transValidationParameter('lname',$this->moduleName),
            'email' => transValidationParameter('email',$this->moduleName),
            'password' => transValidationParameter('password',$this->moduleName),
            'password_confirmation' => transValidationParameter('password_confirmation',$this->moduleName),
            'image' => transValidationParameter('image',$this->moduleName),
            'phone_number' => transValidationParameter('phone',$this->moduleName),
            'account_type' => transValidationParameter('account_type',$this->moduleName),
            'login_service_id' => transValidationParameter('login_service_id',$this->moduleName),

            //Educator
            'bio' => transValidationParameter('bio',$this->moduleName),
            //ForgetPassword
            'account_confirmation_code' => transValidationParameter('account_confirmation_code',$this->moduleName),

            //School
            'school_name' => transValidationParameter('school_name',$this->moduleName),

            //Student
            'type' => transValidationParameter('type',$this->moduleName),
            'parent_email' => transValidationParameter('parent_email',$this->moduleName),

            //SchoolStudent
            FileServices::getFileFieldName() => transValidationParameter('file',$this->moduleName),

        ];
    }
}

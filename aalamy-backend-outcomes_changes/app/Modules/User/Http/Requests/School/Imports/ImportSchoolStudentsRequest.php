<?php

namespace Modules\User\Http\Requests\School\Imports;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\ImportStudentFromExcelModuleClass;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\User\Http\Controllers\Classes\ImportStudentClasses\ExcelFile;
use Modules\User\Http\Controllers\Classes\ImportStudentClasses\FileInterface;
use Modules\User\Http\Controllers\Classes\ImportStudentClasses\FileServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Traits\ValidationAttributesTrans;

class ImportSchoolStudentsRequest extends FormRequest
{

    /**
     * @uses ResponseValidationFormRequest it is responsible to return validation
     * messages error as json
     * @uses AuthorizesAfterValidation it is responsible to call authorizeValidated
     * after check on validation rules
     * @uses ValidationAttributesTrans it is responsible to translate the parameters
     * in rule array
     */
    use ResponseValidationFormRequest,AuthorizesAfterValidation,ValidationAttributesTrans;
    protected $fileType ;
    protected FileInterface $fileClass;

    /**
     * we want to authorize before validation to ensure the file type is valid
     */
    public function authorize()
    {
        $user = $this->user();
        UserServices::checkRoles($user,['school','teacher']);

        if(isset($this->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $studentPermissionClass = new StudentPermissionClass($teacher);
            $studentPermissionClass->checkIfHavePermission('import')
                ->checkImport();
        }else{
            $importStudentFromExcelClass = ImportStudentFromExcelModuleClass::createByOwner($user);
            $importStudentFromExcelClass->check();
        }


        //we have initialized here because we need the rules from it to validate on it
        $this->fileType = $this->route('file_type');
        $this->setFileClass(
            FileServices::createFileClassByType($this->fileType,'school')
        );

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->fileClass->validationRules();

    }

    public function setFileClass(FileInterface $fileClass){
        $this->fileClass = $fileClass;
    }

    public function getFileClass(){
        return $this->fileClass;
    }

}

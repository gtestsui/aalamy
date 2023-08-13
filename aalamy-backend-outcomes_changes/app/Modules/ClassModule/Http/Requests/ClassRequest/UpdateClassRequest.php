<?php

namespace Modules\ClassModule\Http\Requests\ClassRequest;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Models\Level;
use Modules\User\Http\Controllers\Classes\UserServices;

class UpdateClassRequest extends FormRequest
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

    protected $classModel;
    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {
        $user = $this->user();
        UserServices::checkRoles($user,config('ClassModule.panel.owners_class_type'));
        $class = ClassModel::findOrFail($this->route('id'));

        ClassServices::checkUpdateClassAuthorization($user,$class->level_id,$this->my_teacher_id);

        //here if the level is changed then we need to check on the new level authorization
        if($class->level_id != $this->level_id)
            ClassServices::checkUpdateClassAuthorization($user,$this->level_id);


        $this->setClass($class);
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'level_id' => 'required|exists:levels,id',
            'level_id' => 'required|exists:'.(new Level())->getTable().',id',
            'name' => 'required',
        ];
    }

    public function getClass(){
        return $this->classModel;
    }

    public function setClass(ClassModel $classModel){
        $this->classModel = $classModel;
    }

}

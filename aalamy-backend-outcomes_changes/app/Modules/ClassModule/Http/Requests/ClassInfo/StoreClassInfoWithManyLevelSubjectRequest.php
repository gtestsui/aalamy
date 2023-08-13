<?php

namespace Modules\ClassModule\Http\Requests\ClassInfo;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\LevelSubject;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreClassInfoWithManyLevelSubjectRequest extends FormRequest
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
        $class = ClassModel::find($this->route('class_id'));
        ClassServices::checkOwnerClassAuthorization($user,$class->level_id);

        foreach ($this->level_subject_ids as $levelSubjectId){
            $levelSubject = LevelSubject::findOrFail($levelSubjectId);
            LevelServices::checkOwnerLevelSubjectAuthorization($user,$levelSubject);
        }


        if(UserServices::isSchool($user)){
            if(!isset($this->teacher_id))
                throw new ErrorMsgException(transMsg('teacher_id_required',ApplicationModules::CLASS_MODULE_NAME));
            $teacher = Teacher::find($this->teacher_id);
            ClassServices::checkOwnerTeacherAuthorization($user->School,$teacher);
        }

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
//            'class_id' => 'required|exists:classes,id',
//            'level_subject_id' => 'required|exists:level_subjects,id',
            'level_subject_ids' => 'required|array',
            'level_subject_ids.*' => 'required',
            //teacher_id is required just if my account type is school
//            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
        ];
    }

}

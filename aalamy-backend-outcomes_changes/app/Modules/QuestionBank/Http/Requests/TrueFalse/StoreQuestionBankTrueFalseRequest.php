<?php

namespace Modules\QuestionBank\Http\Requests\TrueFalse;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreQuestionBankTrueFalseRequest extends FormRequest
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
//        UserServices::checkRoles($user,config('ClassModule.panel.owners_class_type'));
////        $class = ClassModel::find($this->class_id);
//        $class = ClassModel::find($this->route('class_id'));
//        ClassServices::checkOwnerClassAuthorization($user,$class->level_id);
//
//        $levelSubject = LevelSubject::findOrFail($this->level_subject_id);
//        LevelServices::checkOwnerLevelSubjectAuthorization($user,$levelSubject);
//
//        if(UserServices::isSchool($user)){
//            if(!isset($this->teacher_id))
//                throw new ErrorMsgException(transMsg('teacher_id_required',ApplicationModules::CLASS_MODULE_NAME));
//            $teacher = Teacher::find($this->teacher_id);
//            ClassServices::checkOwnerTeacherAuthorization($user->School,$teacher);
//        }

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

            'true_false_status' => 'required|boolean',

        ];
    }
}

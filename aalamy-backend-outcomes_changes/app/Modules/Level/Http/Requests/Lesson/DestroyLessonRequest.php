<?php

namespace Modules\Level\Http\Requests\Lesson;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Lesson;
use Modules\User\Http\Controllers\Classes\UserServices;

class DestroyLessonRequest extends FormRequest
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

    protected  $lesson;
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
        UserServices::checkRoles($user,config('Level.panel.owners_lesson_type'));
        $lesson = Lesson::findOrFail($this->route('id'));

        //we use teacher id to know if the educator is teacher or is educator
        LevelServices::checkUpdateLessonAuthorization($user,$lesson,$this->my_teacher_id);
//        LevelServices::checkOwnerLessonAuthorization($user,$lesson,$this->my_teacher_id);

        $this->setLesson($lesson);
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
        ];
    }

    public function setLesson(Lesson $lesson){
        $this->lesson = $lesson;
    }

    public function getLesson(){
        return $this->lesson;
    }
}

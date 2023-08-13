<?php

namespace Modules\StudentAchievement\Http\Requests\StudentAchievement;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\StudentAchievement\Traits\ValidationAttributesTrans;
use Modules\StudentAchievement\Http\Controllers\Classes\StudentAchievementServices;
use Modules\StudentAchievement\Models\StudentAchievement;

class UpdateStudentAchievementRequest extends FormRequest
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

    protected $studentAchievement;
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
        $achievement = StudentAchievement::findOrFail($this->route('id'));
        StudentAchievementServices::checkEditStudentAchievement($user,$achievement);
        $this->setStudentAchievement($achievement);
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
            'title' => 'required',
            'description' => 'required',
            'file' => 'nullable|file',
            'file_type' => ['required_with:file',Rule::in(config('StudentAchievement.panel.achievements_file_types'))],

        ];
    }

    public function getStudentAchievement(){
        return $this->studentAchievement;
    }

    public function setStudentAchievement(StudentAchievement $studentAchievement){
        $this->studentAchievement = $studentAchievement;
    }
}

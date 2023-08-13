<?php

namespace Modules\Feedback\Http\Requests\FeedbackAboutStudent;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Feedback\Http\Controllers\Classes\FeedbackServices;
use Modules\Feedback\Http\Controllers\Classes\ManageFeedback\FeedbackAboutStudentManagementFactory;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Feedback\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class UpdateFeedbackAboutStudentRequest extends FormRequest
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
    private FeedbackAboutStudent $feedback;
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
        UserServices::checkRoles($user,['educator','school']);
        $feedback = FeedbackAboutStudent::findOrFail($this->route('id'));

        $manageFeedbackClass = FeedbackAboutStudentManagementFactory::create(
            $user,$this->my_teacher_id
        );
        $manageFeedbackClass->checkUpdateFeedbackAboutStudent($feedback);

        $this->setFeedback($feedback);

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
//            'student_id' => 'required|exists:students,id',
            'text' => 'required|string',
//            'from_date' => 'required|date|before_or_equal:'.date('Y-m-d'),
//            'to_date' => 'required|date|after:from_date',
            'share_with_parent' => 'required|boolean',
//            'file' => 'nullable|file',
//            'image' => 'nullable|image',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setFeedback(FeedbackAboutStudent $feedback){
        $this->feedback = $feedback;
    }

    public function getFeedback(){
        return $this->feedback;
    }
}

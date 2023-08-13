<?php

namespace Modules\Feedback\Http\Requests\FeedbackAboutStudent;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Models\Assignment;
use Modules\Feedback\Http\Controllers\Classes\FeedbackServices;
use Modules\Feedback\Http\Controllers\Classes\ManageFeedback\FeedbackAboutStudentManagementFactory;
use Modules\Feedback\Traits\ValidationAttributesTrans;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class StoreFeedbackAboutStudentRequest extends FormRequest
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
        UserServices::checkRoles($user,['educator','school']);

        $studentManageClass = StudentManagementFactory::create($user);
        $studentManageClass->myStudentByStudentIdOrFail($this->student_id);

        $manageFeedbackClass = FeedbackAboutStudentManagementFactory::create(
            $user,$this->my_teacher_id
        );
        $manageFeedbackClass->checkAddFeedbackAboutStudent($this->student_id);


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
            'student_id' => 'required|exists:'.(new Student())->getTable().',id',
            'text' => 'required|string',
            'from_date' => 'nullable|date|before_or_equal:'.date('Y-m-d'),
            'to_date' => 'nullable|required_with:from_date|date|after_or_equal:from_date|before_or_equal:'.date('Y-m-d'),
            'share_with_parent' => 'nullable|boolean',

            'file' => 'nullable|file',
            'image' => 'nullable|image',

            'roster_assignment_ids' => 'nullable|array',
            'roster_assignment_ids.*' => 'exists:'.(new RosterAssignment())->getTable().',id',

            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }



}

<?php

namespace App\Modules\Meeting\Http\Requests;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\FlashCard\Traits\ValidationAttributesTrans;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\MeetingAttendeeCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\MeetingCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class StoreAndStartMeetingRequest extends FormRequest
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

        // $classManagmentClass = ClassManagementFactory::create($user);
        // $class = $classManagmentClass->myClassesById($this->class_id);
        // if(is_null($class)){
        //     throw new ErrorUnAuthorizationException();
        // }

        $parentsCounts = isset($this->parent_ids)?count($this->parent_ids):0;
        $studentsCounts = isset($this->student_ids)?count($this->student_ids):0;
        $teachersCounts = isset($this->teacher_ids)?count($this->teacher_ids):0;
        $attendeeCount = $parentsCounts +
            $studentsCounts+
            $teachersCounts;


        $meetingCountModuleClass = PlanConstraintsManagementFactory::createMeetingCountModule($user,$this->my_teacher_id);
        $meetingCountModuleClass->check();

        $meetingAttendeeCountModuleClass = PlanConstraintsManagementFactory::createMeetingAttendeeCountModule($user,$this->my_teacher_id);
        $meetingAttendeeCountModuleClass->setRequestedAttendeeCount($attendeeCount)
            ->check();


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
//            'moderator_password' => 'required',
//            'attendee_password' => 'required',
//            'max_participants' => 'required',
            'title' => 'required|min:4',
            'class_id' => 'nullable|exists:classes,id',


            'parent_ids' => 'array',
            'parent_ids.*' => 'exists:'.(new ParentModel())->getTable().',id',
            'student_ids' => 'array',
            'student_ids.*' => 'exists:'.(new Student())->getTable().',id',
            //this array its just if my account type is school
            //because  just the school can add teacher as targets
            'teacher_ids' => 'array',
            'teacher_ids.*' => 'exists:'.(new Teacher())->getTable().',id',


        ];
    }
}

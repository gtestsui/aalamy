<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAttendance;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetRosterAssignmentAttendanceRequest extends FormRequest
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
        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $rosterAssignment = $rosterAssignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentId($this->route('roster_assignment_id'));

        if(is_null($rosterAssignment))
            throw new ErrorUnAuthorizationException();


        $downloadAttendanceFileModuleClass = PlanConstraintsManagementFactory::createDownloadAttendanceFileModule($user,$this->my_teacher_id);
        $downloadAttendanceFileModuleClass->check();

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

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

}

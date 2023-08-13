<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAttendance;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\Roster\Models\Roster;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetRosterAttendanceRequest extends FormRequest
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

    private Roster $roster;

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
        $rosterClass =RosterManagementFactory::create($user);
        $roster = $rosterClass->myRosterById($this->route('roster_id'));

        if(is_null($roster))
            throw new ErrorUnAuthorizationException();

        $downloadAttendanceFileModuleClass = PlanConstraintsManagementFactory::createDownloadAttendanceFileModule($user,$this->my_teacher_id);
        $downloadAttendanceFileModuleClass->check();

        $this->setRoster($roster);

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

            'start_date' => 'date_format:Y/m/d',
            'end_date' => 'date_format:Y/m/d',

            'roster_assignment_ids' => 'nullable|array',


//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setRoster(Roster $roster){
        $this->roster = $roster;
    }

    public function getRoster(){
        return $this->roster;
    }

}

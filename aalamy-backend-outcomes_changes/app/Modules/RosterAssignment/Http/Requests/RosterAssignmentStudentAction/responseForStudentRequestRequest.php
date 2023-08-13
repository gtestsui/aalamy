<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAction;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAction;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class responseForStudentRequestRequest extends FormRequest
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


    private RosterAssignmentStudentAction $rosterAssignmentStudentAction;
//    private User $user;
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
        UserServices::checkRoles($user,['educator']);

        //I have permission to this roster assignments
        $rosterAssignment = RosterAssignment::findOrFail($this->route('roster_assignment_id'));
        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $myRosterAssignmentsIds = $rosterAssignmentClass->myRosterAssignmentsIdsByRosterId($rosterAssignment->roster_id);
        if(!in_array($rosterAssignment->id,$myRosterAssignmentsIds))
            throw new ErrorUnAuthorizationException();

        $rosterAssignmentStudentAction = RosterAssignmentStudentAction::where('student_id',$this->route('student_id'))
            ->where('roster_assignment_id',$rosterAssignment->id)
            ->firstOrFail();



        $this->setRosterAssignmentStudentAction($rosterAssignmentStudentAction);
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

    public function setRosterAssignmentStudentAction(RosterAssignmentStudentAction $rosterAssignmentStudentAction){
        $this->rosterAssignmentStudentAction = $rosterAssignmentStudentAction;
    }

    public function getRosterAssignmentStudentAction(){
        return $this->rosterAssignmentStudentAction;
    }

}

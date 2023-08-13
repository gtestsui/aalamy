<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAction;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\StudentRosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Traits\SharedValidationForStoreRosterAssignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\HelpRequestModuleClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class RequestForActionInRosterAssignmentRequest extends FormRequest
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
     * this trait have function to get shared validation array between all store
     * RosterAssignment ValidationRequests
     */
    use SharedValidationForStoreRosterAssignment;

    private RosterAssignment $rosterAssignment;
    private User $user;
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
        UserServices::checkRoles($user,['student']);
        list(,$student) = UserServices::getAccountTypeAndObject($user);
//        $user->load('Student');

        $rosterAssignment = RosterAssignment::findOrFail($this->route('roster_assignment_id'));
        $studentRosterAssignmentClass = new StudentRosterAssignment($student);
        $myRosterAssignmentsIds = $studentRosterAssignmentClass->myRosterAssignmentsIdsByRosterId($rosterAssignment->roster_id);

        if(!in_array($rosterAssignment->id,$myRosterAssignmentsIds))
            throw new ErrorUnAuthorizationException();

        $this->setRosterAssignment($rosterAssignment);
        $this->setUser($user);
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

    public function setRosterAssignment(RosterAssignment $rosterAssignment){
        $this->rosterAssignment = $rosterAssignment;
    }

    public function getRosterAssignment(){
        return $this->rosterAssignment;
    }

    public function setUser(User $user){
        $this->user = $user;
    }

    public function getUser(){
        return $this->user;
    }
}

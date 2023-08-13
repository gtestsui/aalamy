<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignment;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Traits\SharedValidationForStoreRosterAssignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;

class AddAssignmentToManyRostersRequest extends FormRequest
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

    private Assignment $assignment;

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

        $assignment = Assignment::findOrFail($this->assignment_id);

        AssignmentServices::checkAddRosterAssignmentAuthorization($assignment,$user,$this->my_teacher_id);
        $this->setAssignment($assignment);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $sharedRulesForStore = $this->getSharedValidationRuleArrayForStore();
        $myRules = [
//            'assignment_id' => 'required|exists:assignments,id',
            'assignment_id' => 'required|exists:'.(new Assignment())->getTable().',id',
            'roster_ids' => 'required|array',
//            'roster_ids.*' => 'required|exists:rosters,id',
            'roster_ids.*' => 'required|exists:'.(new Roster())->getTable().',id',

        ];
        return array_merge($sharedRulesForStore,$myRules);

    }

    public function setAssignment(Assignment $assignment){
        $this->assignment = $assignment;
    }

    public function getAssignment(){
        return $this->assignment;
    }
}

<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignment;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Traits\SharedValidationForStoreRosterAssignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;

class LinkRosterToManyAssignmentsRequest extends FormRequest
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

//        $assignment = Assignment::findOrFail($this->assignment_id);
        $roster = Roster::findOrFail($this->roster_id);
        RosterServices::checkUseRosterAuthorization($roster,$user,$this->my_teacher_id);

//        AssignmentServices::checkAddRosterAssignmentAuthorization($assignment,$user,$this->my_teacher_id);
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

        $sharedRulesForStore = $this->getSharedValidationRuleArrayForStore();
        $myRules = [
//            'roster_id' => 'required|exists:rosters,id',
            'roster_id' => 'required|exists:'.(new Roster())->getTable().',id',
            'assignment_ids' => 'required|array',
//            'assignment_ids.*' => 'required|exists:assignments,id',
            'assignment_ids.*' => 'required|exists:'.(new Assignment())->getTable().',id',

        ];
        return array_merge($sharedRulesForStore,$myRules);

//        return [
//            'roster_id' => 'required|exists:rosters,id',
//            'assignment_ids' => 'required|array',
//            'assignment_ids.*' => 'required|exists:assignments,id',
//
//            'is_locked' => 'boolean',
//            'is_hidden' => 'boolean',
//            'prevent_request_help' => 'boolean',
//            'display_mark' => 'boolean',
//            'is_auto_saved' => 'boolean',
//            'prevent_moved_between_pages' => 'boolean',
//            'is_shuffling' => 'boolean',
//
//            'start_date' => ['required','after_or_equal:'.date('Y-m-d'),'date_format:'.config('panel.standard_date_time_format')],
//            'expiration_date' => ['required','after:start_date','date_format:'.config('panel.standard_date_time_format')],
//
//            'my_teacher_id' => 'nullable|exists:teachers,id',
//
//        ];
    }

    public function setRoster(Roster $roster){
        $this->roster = $roster;
    }

    public function getRoster(){
        return $this->roster;
    }
}

<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignment;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetByRosterIdRequest extends FormRequest
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
        UserServices::checkRoles($user,['educator','school','student']);
        $assignmentClass = RosterManagementFactory::create($user);
        $roster = $assignmentClass->myRosterByIdOrFail($this->roster_id);

//        $this->setRoster($roster);
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
//            'roster_id' => 'required|exists:rosters,id',
            'roster_id' => 'required|exists:'.(new Roster())->getTable().',id',

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

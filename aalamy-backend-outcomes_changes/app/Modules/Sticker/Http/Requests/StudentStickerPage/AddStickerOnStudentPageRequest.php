<?php

namespace Modules\Sticker\Http\Requests\StudentStickerPage;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Traits\SharedValidationForStoreRosterAssignment;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;

class AddStickerOnStudentPageRequest extends FormRequest
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
        $myRosterAssignment = $rosterAssignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentId($this->route('roster_assignment_id'));
        if(is_null($myRosterAssignment))
            throw new ErrorUnAuthorizationException();


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




}

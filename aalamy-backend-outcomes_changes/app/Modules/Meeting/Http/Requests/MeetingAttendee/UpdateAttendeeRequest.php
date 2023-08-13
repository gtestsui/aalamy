<?php

namespace App\Modules\Meeting\Http\Requests\MeetingAttendee;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FlashCard\Traits\ValidationAttributesTrans;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\MeetingOwnerManagementFactory;
use Modules\Meeting\Models\MeetingTargetUser;
use Modules\User\Http\Controllers\Classes\UserServices;

class UpdateAttendeeRequest extends FormRequest
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


    protected MeetingTargetUser $meetingTarget;
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

        $meetingTarget = MeetingTargetUser::findOrFail($this->route('meeting_targeted_id'));
        $meetingManagmentClass = MeetingOwnerManagementFactory::create($user);
        $meet = $meetingManagmentClass->getMyMeetingById($meetingTarget->meeting_id);
        if(is_null($meet)){
            throw new ErrorUnAuthorizationException();
        }
        $this->setMeetingTarget($meetingTarget);
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
            'attendee_status' => 'required|boolean',
            'note' => 'nullable',

        ];
    }

    public function setMeetingTarget(MeetingTargetUser $meetingTarget){
        $this->meetingTarget = $meetingTarget;
    }

    public function getMeetingTarget(){
        return $this->meetingTarget;
    }


}

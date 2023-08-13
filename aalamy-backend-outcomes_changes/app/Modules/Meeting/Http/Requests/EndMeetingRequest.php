<?php

namespace App\Modules\Meeting\Http\Requests;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FlashCard\Traits\ValidationAttributesTrans;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\MeetingManagementFactory;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\UserServices;

class EndMeetingRequest extends FormRequest
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


    private Meeting $meeting;
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
        $meetingClass = MeetingManagementFactory::create($user);
        $meeting = $meetingClass->getMyMeetingByIdOrFail($this->route('meeting_id'));

        $this->setMeeting($meeting);
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


    public function getMeeting(){
        return $this->meeting;
    }

    public function setMeeting(Meeting $meeting){
        $this->meeting = $meeting;
    }

}

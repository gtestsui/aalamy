<?php

namespace App\Modules\Meeting\Http\Requests;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FlashCard\Traits\ValidationAttributesTrans;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers\MeetingTargetManagementFactory;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\UserServices;

class JoinAsAttendeeRequest extends FormRequest
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
//        UserServices::checkRoles($user,['educator','school']);
        $meetingTargetClass = MeetingTargetManagementFactory::create($user,$this->my_teacher_id);
        $meeting = $meetingTargetClass->getMeetingsTargetMeById($this->route('meeting_id'));
        if(is_null($meeting))
            throw new ErrorUnAuthorizationException();
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
//            'attendee_password' => 'required',
        ];
    }


    public function setMeeting(Meeting $meeting){
        $this->meeting = $meeting;
    }

    public function getMeeting(){
        return $this->meeting;
    }



}

<?php

namespace App\Modules\Meeting\Http\Requests\MeetingAttendee;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FlashCard\Traits\ValidationAttributesTrans;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\MeetingOwnerManagementFactory;
use Modules\Meeting\Models\Meeting;
use Modules\Meeting\Models\MeetingTargetUser;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;

class GetStudentAttendeeRequest extends FormRequest
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


    protected Meeting $meeting;
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

        $studentManagmentClass = StudentManagementFactory::create($user);
        $ownerStudent = $studentManagmentClass->myStudentByStudentId($this->route('student_id'));

        if(is_null($ownerStudent)){
            throw new ErrorUnAuthorizationException();
        }
//        $this->setMeeting($meeting);
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


            'meetings_ids' => 'nullable|array',

        ];
    }

    public function setMeeting(Meeting $meeting){
        $this->meeting = $meeting;
    }

    public function getMeeting(){
        return $this->meeting;
    }


}

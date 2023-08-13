<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentAction;


use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class CheckAnswerRequestClass extends StudentRequestsAbstract
{


    public function __construct(User $user,$rosterAssignment)
    {
        $this->user = $user;
        list(,$this->student) = UserServices::getAccountTypeAndObject($user);
//        $this->student = $this->user->Student;
        $this->rosterAssignment = $rosterAssignment;
        $this->actionRequestColumnName = 'check_answer_request';
        $this->prepareOldAction();
    }

    protected function checkDoesntFoundAnotherActions(){//
        if($this->oldAction->help_request)
            return false;
        return true;
    }


    public function sendNotification(){
        if(isset($this->oldAction) && !$this->checkImTryingToCancelMyRequest()){
            //here send notification
        }
    }



}

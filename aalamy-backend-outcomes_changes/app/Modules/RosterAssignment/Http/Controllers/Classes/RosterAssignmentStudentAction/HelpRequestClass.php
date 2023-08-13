<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentAction;


use Modules\RosterAssignment\Models\RosterAssignmentStudentAction;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class HelpRequestClass extends StudentRequestsAbstract
{


    public function __construct(User $user,$rosterAssignment)
    {
        $this->user = $user;
//        $this->student = $this->user->Student;
        list(,$this->student) = UserServices::getAccountTypeAndObject($user);
        $this->rosterAssignment = $rosterAssignment;
        $this->actionRequestColumnName = 'help_request';
        $this->prepareOldAction();
    }

    protected function checkDoesntFoundAnotherActions(){//
        if($this->oldAction->check_answer_request)
            return false;
        return true;
    }

    public function sendNotification(){
        if(isset($this->oldAction) && !$this->checkImTryingToCancelMyRequest()){
            //here send notification
        }
    }


}

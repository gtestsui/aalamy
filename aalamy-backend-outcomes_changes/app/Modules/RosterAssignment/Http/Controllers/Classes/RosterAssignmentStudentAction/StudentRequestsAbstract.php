<?php


namespace Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentAction;


use Modules\RosterAssignment\Models\RosterAssignmentStudentAction;
use Modules\RosterAssignment\Models\Rosterassignment;
use Modules\User\Models\User;
use Modules\User\Models\Student;

abstract class StudentRequestsAbstract
{

    /**
     * @var  RosterAssignmentStudentAction|null $oldAction
     */
    protected ?RosterAssignmentStudentAction $oldAction;
    protected User $user;
    protected Student $student;
    protected Rosterassignment $rosterAssignment;
    protected string $actionRequestColumnName;

    abstract protected function checkDoesntFoundAnotherActions();


    protected function prepareOldAction(){
        $this->oldAction = RosterAssignmentStudentAction::where('student_id',$this->student->id)
            ->where('roster_assignment_id',$this->rosterAssignment->id)
            ->first();
    }


    /**
     * if there is an record contains the current action
     * delete the record from data if there doesn't found any actions else the current action
     * else opposite the value of the action between (true,false)
     * and if no record has contains the current action so create no one
     * @return RosterAssignmentStudentAction|null
     */
    public function makeAction(){

        if($this->checkIsThereAnOldAction()){
            if($this->shouldDeleteTheOldAction()){
                $this->oldAction->delete();
                $this->oldAction=null;
            }else{
                $this->oppositeCurrentRequestStatus();
            }
            return $this->oldAction;
        }else{
            return $this->createNewAction();
        }
    }

    protected function checkIsThereAnOldAction(){
        if (is_null($this->oldAction))
            return false;
        return true;
    }

    protected function shouldDeleteTheOldAction(){
        if($this->checkDoesntFoundAnotherActions() && $this->checkImTryingToCancelMyRequest())
            return true;
        return false;
    }

    /**
     * if the original value its true so that mean I'm trying to make it false(cancel the request)
     */
    protected function checkImTryingToCancelMyRequest(){
        if($this->oldAction->{$this->actionRequestColumnName})
            return true;
        return false;
    }

    /**
     * opposite the value of the action between (true,false)
     */
    protected function oppositeCurrentRequestStatus(){
        $this->oldAction->update([
            $this->actionRequestColumnName => !$this->oldAction->{$this->actionRequestColumnName}
        ]);
    }

    /**
     * create new record in RosterAssignmentStudentAction table
     * @return RosterAssignmentStudentAction
     */
    protected function createNewAction(){
        return RosterAssignmentStudentAction::create([
            'roster_assignment_id' => $this->rosterAssignment->id,
            'student_id' => $this->student->id,
            $this->actionRequestColumnName => true,
        ]);
    }


}

<?php

namespace Modules\RosterAssignment\Observers;

use App\Http\Controllers\Classes\ServicesClass;
use Modules\Notification\Http\Controllers\Classes\RosterAssignmentStudentAction\SendCheckAnswerRequestNotification;
use Modules\Notification\Jobs\RosterAssignmentStudentAction\SendNewCheckAnswerRequestNotification;
use Modules\Notification\Jobs\RosterAssignmentStudentAction\SendNewHelpRequestNotification;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAction;

class RosterAssignmentStudentActionObserver
{


    /**
     * Handle the Product "created" event.
     *
     * @param  RosterAssignmentStudentAction  $product
     * @return void
     */
    public function creating(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {


    }

    /**
     * Handle the User "created" event.
     *
     * @param  RosterAssignmentStudentAction  $rosterAssignmentStudentAction
     * @return void
     * check if the user logged in by outer service or register normal way
     */
    public function created(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {

        if($rosterAssignmentStudentAction->help_request === true){
            ServicesClass::dispatchJob(new SendNewHelpRequestNotification($rosterAssignmentStudentAction));

        }

        if($rosterAssignmentStudentAction->check_answer_request === true){
            ServicesClass::dispatchJob(new SendNewCheckAnswerRequestNotification($rosterAssignmentStudentAction));

        }

    }

    /**
     * Handle the User "updated" event.
     *
     * @param  RosterAssignmentStudentAction  $rosterAssignmentStudentAction
     * @return void
     */
    public function updated(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {

        if($rosterAssignmentStudentAction->wasChanged('help_request') && $rosterAssignmentStudentAction->help_request){
            // help_request has changed
            ServicesClass::dispatchJob(new SendNewHelpRequestNotification($rosterAssignmentStudentAction));
        }

        if($rosterAssignmentStudentAction->wasChanged('check_answer_request') && $rosterAssignmentStudentAction->check_answer_request){
            // check_answer_request has changed
            ServicesClass::dispatchJob(new SendNewCheckAnswerRequestNotification($rosterAssignmentStudentAction));
        }


    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  RosterAssignmentStudentAction  $rosterAssignmentStudentAction
     * @return void
     */
    public function deleted(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {


    }

    /**
     * Handle the User "restored" event.
     *
     * @param  RosterAssignmentStudentAction  $rosterAssignmentStudentAction
     * @return void
     */
    public function restored(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {


    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  RosterAssignmentStudentAction  $rosterAssignmentStudentAction
     * @return void
     */
    public function forceDeleted(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {
        //
    }
}

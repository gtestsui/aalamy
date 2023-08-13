<?php

namespace Modules\RosterAssignment\Http\Controllers;


use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentAction\CheckAnswerRequestClass;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentAction\HelpRequestClass;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAction\RequestForActionInRosterAssignmentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAction\responseForStudentRequestRequest;
use Modules\RosterAssignment\Observers\RosterAssignmentStudentActionObserver;

class RosterAssignmentStudentActionController extends Controller
{


    /**
     * @see RosterAssignmentStudentActionObserver for notification
     * @param RequestForActionInRosterAssignmentRequest $request
     * @param $roster_assignment_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestForHelp(RequestForActionInRosterAssignmentRequest $request,$roster_assignment_id){
        $user = $request->user();
        $rosterAssignment = $request->getRosterAssignment();
        $helpRequestClass = new HelpRequestClass($user,$rosterAssignment);
        $helpRequestClass->makeAction();
//        $helpRequestClass->sendNotification();
//        if($t !== true && $t->help_request === true)
        return ApiResponseClass::successMsgResponse();

    }

    /**
     * @see RosterAssignmentStudentActionObserver for notification
     * @param RequestForActionInRosterAssignmentRequest $request
     * @param $roster_assignment_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestForCheckAnswer(RequestForActionInRosterAssignmentRequest $request,$roster_assignment_id){
        $user = $request->user();
        $rosterAssignment = $request->getRosterAssignment();
        $checkAnswerRequestClass = new CheckAnswerRequestClass($user,$rosterAssignment);
        $checkAnswerRequestClass->makeAction();
        return ApiResponseClass::successMsgResponse();

    }

        public function responseForStudentRequest(responseForStudentRequestRequest $request,$roster_assignment_id,$student_id){
        $user = $request->user();
        $rosterAssignmentStudentAction = $request->getRosterAssignmentStudentAction();
        $rosterAssignmentStudentAction->delete();
        return ApiResponseClass::successMsgResponse();
    }


}

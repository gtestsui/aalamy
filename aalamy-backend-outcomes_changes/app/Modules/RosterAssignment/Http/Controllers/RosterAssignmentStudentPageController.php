<?php

namespace Modules\RosterAssignment\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\RosterAssignment\Http\Requests\StudentPage\actionByPageForDefinedRosterStudentsRequest;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;

class RosterAssignmentStudentPageController extends Controller
{


    /**
     * @param string $action is enum of (hide , lock)
     * hide or unHide
     * unHide from all then hide from roster_student_ids
     */
    public function actionByPageForDefinedRosterStudents(
        actionByPageForDefinedRosterStudentsRequest $request,
        $roster_assignment_id,
        $page_id,
        $action)
    {
        $user = $request->user();

        $rosterAssignmentPage = $request->getRosterAssignmentPage();

        //delete the old action (un hide or un lock) on all old data
//        RosterAssignmentStudentPage::where('roster_assignment_page_id',$rosterAssignmentPage->id)
//            ->{'un'.ucfirst($action)}();

        if(!is_null($request->student_ids) && count($request->student_ids)){
            //then make the action in new roster_students from request
            $rosterAssignmentStudentPage = RosterAssignmentStudentPage::where('roster_assignment_page_id',$rosterAssignmentPage->id)
                ->whereIn('student_id',$request->student_ids)
                ->firstOrFail();
//                ->$action();

            if($request->action == 'hide'){
                $rosterAssignmentStudentPage->update([
                   'is_hidden' => !$rosterAssignmentStudentPage->is_hidden
                ]);
            }else{
                $rosterAssignmentStudentPage->update([
                    'is_locked' => !$rosterAssignmentStudentPage->is_locked
                ]);

            }

        }

        return ApiResponseClass::successMsgResponse();
    }


}

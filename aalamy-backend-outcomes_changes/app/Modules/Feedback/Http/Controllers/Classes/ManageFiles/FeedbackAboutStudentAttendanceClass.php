<?php

namespace Modules\Feedback\Http\Controllers\Classes\ManageFiles;


use Modules\Feedback\Http\DTO\FeedbackAboutStudentData;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Feedback\Models\FeedbackAboutStudentAttendance;
use Modules\RosterAssignment\Http\Controllers\Classes\Attendance\StudentAttendanceClass;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\User\Models\User;

class FeedbackAboutStudentAttendanceClass implements FeedbackAboutStudentFileInterface
{

    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    public function addToFeedback(FeedbackAboutStudent $feedback,FeedbackAboutStudentData $feedbackAboutStudentData){

        $rosterAssignmentIds = $feedbackAboutStudentData->roster_assignment_ids;

        $filterRosterAssignmentAttendanceData = FilterRosterAssignmentAttendanceData::fromArray([
            'roster_assignment_ids' => $rosterAssignmentIds,
        ]);
        $studentAttendanceClass = new StudentAttendanceClass(
            $feedbackAboutStudentData->student_id,
            $this->user,
            $filterRosterAssignmentAttendanceData
        );

        $innerPath = $studentAttendanceClass->exportAsExcel();



        return FeedbackAboutStudentAttendance::create([
            'feedback_id' => $feedback->id,
            'attendance_file' => $innerPath,
        ]);
    }

    public function deleteFromFeedback($id){
        return FeedbackAboutStudentAttendance::findOrFail($id)->delete();
    }


}

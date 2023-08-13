<?php

namespace Modules\Feedback\Http\Controllers\Classes\ManageFiles;


use Modules\Feedback\Http\DTO\FeedbackAboutStudentData;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Feedback\Models\FeedbackAboutStudentMark;
use Modules\Mark\Http\Controllers\Classes\Mark\StudentMarkClass;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class FeedbackAboutStudentMarkClass implements FeedbackAboutStudentFileInterface
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
        $student = Student::findOrFail($feedbackAboutStudentData->student_id);
        $studentMarkClass = new StudentMarkClass(
            $student,
            $this->user,
            $filterRosterAssignmentAttendanceData
        );

        $innerPath = $studentMarkClass->exportAsExcel();



        return FeedbackAboutStudentMark::create([
            'feedback_id' => $feedback->id,
            'mark_file' => $innerPath,
        ]);
    }

    public function deleteFromFeedback($id){
        return FeedbackAboutStudentMark::findOrFail($id)->delete();
    }


}

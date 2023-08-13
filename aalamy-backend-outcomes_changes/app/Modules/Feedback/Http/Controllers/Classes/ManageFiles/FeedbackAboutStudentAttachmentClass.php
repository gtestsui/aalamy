<?php

namespace Modules\Feedback\Http\Controllers\Classes\ManageFiles;


use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\Feedback\Http\DTO\FeedbackAboutStudentData;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Feedback\Models\FeedbackAboutStudentAttendance;
use Modules\Feedback\Models\FeedbackAboutStudentFile;
use Modules\RosterAssignment\Http\Controllers\Classes\Attendance\StudentAttendanceClass;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\StudentRosterAssignment;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class FeedbackAboutStudentAttachmentClass
{

    private $feedbackData;
    private $feedback;

    public function __construct(FeedbackAboutStudent $feedback,FeedbackAboutStudentData $feedbackData){
        $this->feedbackData = $feedbackData;
        $this->feedback = $feedback;
    }


    public function addImageToFeedback(){

        if(is_null($this->feedbackData->image))
            return false;
        $feedbackImage = new FeedbackAboutStudentImageClass();
        $feedbackImage->addToFeedback(
            $this->feedback,$this->feedbackData
        );

    }

    public function addFileToFeedback(){
        if(is_null($this->feedbackData->file))
            return false;
        $feedbackFile = new FeedbackAboutStudentFileClass();
        $feedbackFile->addToFeedback(
            $this->feedback,$this->feedbackData
        );

    }

    public function addAttendanceToFeedback(User $user){
        if(!count($this->feedbackData->roster_assignment_ids))
            return false;

        $feedbackFile = new FeedbackAboutStudentAttendanceClass($user);
        $feedbackFile->addToFeedback(
            $this->feedback,$this->feedbackData
        );

    }

    public function addMarksToFeedback(User $user){
        if(!count($this->feedbackData->roster_assignment_ids))
            return false;

        $feedbackFile = new FeedbackAboutStudentMarkClass($user);
        $feedbackFile->addToFeedback(
            $this->feedback,$this->feedbackData
        );

    }


    public function deleteFileFromFeedback($fileId){
        return FeedbackAboutStudentFile::findOrFail($fileId)->delete();
    }


}

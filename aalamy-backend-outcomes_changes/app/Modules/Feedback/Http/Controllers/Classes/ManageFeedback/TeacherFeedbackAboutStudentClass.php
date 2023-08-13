<?php

namespace Modules\Feedback\Http\Controllers\Classes\ManageFeedback;



use App\Exceptions\ErrorUnAuthorizationException;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;

use Modules\User\Models\Teacher;

class TeacherFeedbackAboutStudentClass extends BaseFeedbackAboutStudentAbstract implements ManageFeedbackAboutStudentInterface
{

    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function checkAddFeedbackAboutStudent($studentId){
        $studentTeacherClass = new StudentTeacherClass($this->teacher);
        $myStudentById = $studentTeacherClass->myStudentByStudentId($studentId);
        if(is_null($myStudentById))
            throw new ErrorUnAuthorizationException();
    }

    public function checkUpdateFeedbackAboutStudent(FeedbackAboutStudent $feedback){
        if($feedback->teacher_id != $this->teacher->id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkDeleteFeedbackAboutStudent(FeedbackAboutStudent $feedback){
        $this->checkUpdateFeedbackAboutStudent($feedback);
    }


    protected function getMyFeedbackQuery(){
        $myFeedbackQuery = FeedbackAboutStudent::query();
        $myFeedbackQuery->where('teacher_id',$this->teacher->id)
            ->with('Student.ParentStudents.Parent');
        return $myFeedbackQuery;
    }



}

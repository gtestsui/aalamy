<?php

namespace Modules\Feedback\Http\Controllers\Classes\ManageFeedback;



use App\Exceptions\ErrorUnAuthorizationException;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;

use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class SchoolFeedbackAboutStudentClass extends BaseFeedbackAboutStudentAbstract  implements ManageFeedbackAboutStudentInterface
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function checkAddFeedbackAboutStudent($studentId){
        $studentSchoolClass = new StudentSchoolClass($this->school);
        $myStudentById = $studentSchoolClass->myStudentByStudentId($studentId);
        if(is_null($myStudentById))
            throw new ErrorUnAuthorizationException();
    }

    public function checkUpdateFeedbackAboutStudent(FeedbackAboutStudent $feedback){
        if($feedback->school_id != $this->school->id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkDeleteFeedbackAboutStudent(FeedbackAboutStudent $feedback){
        $this->checkUpdateFeedbackAboutStudent($feedback);

    }


    protected function getMyFeedbackQuery(){
        $myFeedbackQuery = FeedbackAboutStudent::query();
        $myFeedbackQuery->where('school_id',$this->school->id)
            ->with('Student.ParentStudents.Parent');
        return $myFeedbackQuery;
    }




}

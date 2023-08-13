<?php

namespace Modules\Feedback\Http\Controllers\Classes\ManageFeedback;



use App\Exceptions\ErrorUnAuthorizationException;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;

class EducatorFeedbackAboutStudentClass extends BaseFeedbackAboutStudentAbstract implements ManageFeedbackAboutStudentInterface
{

    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    public function checkAddFeedbackAboutStudent($studentId){
        $studentEducatorClass = new StudentEducatorClass($this->educator);
        $myStudentById = $studentEducatorClass->myStudentByStudentId($studentId);
        if(is_null($myStudentById))
            throw new ErrorUnAuthorizationException();
    }

    public function checkUpdateFeedbackAboutStudent(FeedbackAboutStudent $feedback){
        if($feedback->educator_id != $this->educator->id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkDeleteFeedbackAboutStudent(FeedbackAboutStudent $feedback){
        $this->checkUpdateFeedbackAboutStudent($feedback);

    }


    protected function getMyFeedbackQuery(){
        $myFeedbackQuery = FeedbackAboutStudent::query();
        $myFeedbackQuery->where('educator_id',$this->educator->id)
            ->with('Student.ParentStudents.Parent');
        return $myFeedbackQuery;
    }



}

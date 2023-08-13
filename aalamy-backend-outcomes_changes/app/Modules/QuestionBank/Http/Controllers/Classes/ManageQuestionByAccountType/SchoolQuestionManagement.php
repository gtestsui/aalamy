<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType;

use Illuminate\Database\Eloquent\Builder;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Http\DTO\QuizData;
use Modules\User\Models\School;

class SchoolQuestionManagement extends BaseManageQuestionByAccountTypeAbstract
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function getMyQuestionBankQuery(){
        $questionBankQuery = QuestionBank::query();
        $questionBankQuery->where('school_id',$this->school->id)
        ->whereNull('teacher_id');
        return $questionBankQuery;

    }


//    public function generateQuiz(QuizData $quizData){
//        $this->getMyQuestionBankQuery()
//            ->where('level_subject_id',$quizData->level_subject_id)
//            ->when(isset($quizData->unit_id),function ($query)use ($quizData){
//                return $query->where('unit_id',$quizData->unit_id);
//            })
//            ->when(isset($quizData->lesson_id),function ($query)use ($quizData){
//                return $query->where('lesson_id',$quizData->lesson_id);
//            })
//    }


}

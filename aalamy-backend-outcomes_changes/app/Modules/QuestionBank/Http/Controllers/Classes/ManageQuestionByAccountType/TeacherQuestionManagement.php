<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType;

use Illuminate\Database\Eloquent\Builder;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class TeacherQuestionManagement extends BaseManageQuestionByAccountTypeAbstract
{

    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function getMyQuestionBankQuery(){
        $questionBankQuery = QuestionBank::query();
        $questionBankQuery->where('teacher_id',$this->teacher->id);
        return $questionBankQuery;

    }

//    public function getAllMyQuestionBank(){
//        $questionBankQuery = $this->getMyQuestionBankQuery();
//        $questionBanks = $questionBankQuery->get();
//        return $questionBanks;
//    }

//    public function getMyQuestionBankPaginate(){
//        $questionBankQuery = $this->getMyQuestionBankQuery();
//        $questionBanks = $questionBankQuery->paginate(10);
//        return $questionBanks;
//    }
//
//
//    /**
//     * @return Builder
//     */
//    public function getAllMyQuestionBankByIdQuery($id){
//        $questionBankQuery = $this->getMyQuestionBankQuery();
//        $questionBank = $questionBankQuery->where('id',$id);
//        return $questionBank;
//    }
//
//    public function getAllMyQuestionBankById($id){
//        $questionBankQuery = $this->getAllMyQuestionBankByIdQuery($id);
//        $questionBank = $questionBankQuery->first();
//        return $questionBank;
//    }
//
//    public function getAllMyQuestionBankByIdOrFail($id){
//        $questionBankQuery = $this->getAllMyQuestionBankByIdQuery($id);
//        $questionBank = $questionBankQuery->firstOrFail();
//        return $questionBank;
//    }

}

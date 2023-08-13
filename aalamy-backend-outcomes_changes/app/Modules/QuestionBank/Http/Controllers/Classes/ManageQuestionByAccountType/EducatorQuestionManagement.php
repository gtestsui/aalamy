<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType;

use Illuminate\Database\Eloquent\Builder;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorQuestionManagement extends BaseManageQuestionByAccountTypeAbstract
{

    private Educator $educator;
    private  $myTeacherAccountIds = [];
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;

        $myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
            ->get();
        if(count($myTeacherAccounts)){
            $this->myTeacherAccountIds = $myTeacherAccounts->pluck('id')->toArray();
        }
    }

    public function getMyQuestionBankQuery(){
//        $myTeacherAccountIds = [];
//        $myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
//            ->get();
//        if(count($myTeacherAccounts)){
//            $myTeacherAccountIds = $myTeacherAccounts->pluck('id')->toArray();
//        }

        $questionBankQuery = QuestionBank::query();
        $questionBankQuery->where(function ($query){
          return $query->where('educator_id',$this->educator->id);
//              ->orWhereIn('teacher_id',$this->myTeacherAccountIds);
        });
        return $questionBankQuery;
    }


}

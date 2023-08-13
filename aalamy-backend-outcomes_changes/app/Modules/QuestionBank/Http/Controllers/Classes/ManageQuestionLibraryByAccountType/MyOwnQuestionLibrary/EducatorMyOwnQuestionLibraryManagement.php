<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyOwnQuestionLibrary;

use Illuminate\Database\Eloquent\Collection;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorMyOwnQuestionLibraryManagement extends BaseManageMyOwnQuestionLibraryByAccountTypeAbstract
{

    private Educator $educator;
    private ?Collection $myTeacherAccounts;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;

        $this->myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
            ->get();
    }


    //my shared question with library (I can make actions on them)
    public function getMyQuestionLibraryQuery(){

        $myTeacherAccountIds = [];
        if(count($this->myTeacherAccounts)){
            $myTeacherAccountIds = $this->myTeacherAccounts->pluck('id')->toArray();
        }
        $questionLibraryQuery = LibraryQuestion::query();
        $questionLibraryQuery->myOwnAsEducator($this->educator->id,$myTeacherAccountIds);
        return $questionLibraryQuery;
    }



}

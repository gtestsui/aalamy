<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyAllowedQuestionLibrary;


use Illuminate\Database\Eloquent\Collection;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyOwnQuestionLibrary\EducatorMyOwnQuestionLibraryManagement;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorMyAllowedQuestionLibraryManagement extends BaseManageMyAllowedQuestionLibraryByAccountTypeAbstract
{

    private Educator $educator;
    private ?Collection $myTeacherAccounts;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;

        $this->myTeacherAccounts = Teacher::where('user_id',$this->educator->user_id)
            ->get();
    }


    //my shared and another question I have permission to see it
    public function getMyAllowedQuestionLibraryQuery(){

        $myTeacherAccountIds = [];
        $myTeacherSchoolIds = [];
        if(count($this->myTeacherAccounts)){
            $myTeacherAccountIds = $this->myTeacherAccounts->pluck('id')->toArray();
            $myTeacherSchoolIds = $this->myTeacherAccounts->pluck('school_id')->toArray();
        }



        $questionLibraryQuery = LibraryQuestion::query();
        $questionLibraryQuery->myAllowedAsEducator($this->educator->id,$myTeacherAccountIds,$myTeacherSchoolIds);




        return $questionLibraryQuery;
    }



}

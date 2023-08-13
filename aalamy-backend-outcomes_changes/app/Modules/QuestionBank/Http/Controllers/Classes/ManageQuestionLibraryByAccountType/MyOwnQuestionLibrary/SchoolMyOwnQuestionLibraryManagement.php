<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyOwnQuestionLibrary;


use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\User\Models\School;

class SchoolMyOwnQuestionLibraryManagement extends BaseManageMyOwnQuestionLibraryByAccountTypeAbstract
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    //my shared question with library (I can make actions on them)
    public function getMyQuestionLibraryQuery(){
        $questionBankQuery = LibraryQuestion::query();
        $questionBankQuery->myOwnAsSchool($this->school->id);
        return $questionBankQuery;

    }



}

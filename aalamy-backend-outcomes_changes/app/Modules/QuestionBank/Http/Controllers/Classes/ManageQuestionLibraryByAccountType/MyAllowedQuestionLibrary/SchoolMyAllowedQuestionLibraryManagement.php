<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyAllowedQuestionLibrary;


use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\User\Models\School;

class SchoolMyAllowedQuestionLibraryManagement extends BaseManageMyAllowedQuestionLibraryByAccountTypeAbstract
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }


    //my shared and another question I have permission to see it
    public function getMyAllowedQuestionLibraryQuery(){

        $questionBankQuery = LibraryQuestion::query();
        $questionBankQuery->myAllowedAsSchool($this->school->id);





        return $questionBankQuery;

    }


}

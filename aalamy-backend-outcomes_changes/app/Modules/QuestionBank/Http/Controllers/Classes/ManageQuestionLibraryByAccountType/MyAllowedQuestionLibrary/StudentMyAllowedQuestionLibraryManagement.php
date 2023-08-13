<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionLibraryByAccountType\MyAllowedQuestionLibrary;

use App\Exceptions\ErrorMsgException;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\User\Http\Controllers\Classes\StudentClass;
use Modules\User\Models\Student;

class StudentMyAllowedQuestionLibraryManagement extends BaseManageMyAllowedQuestionLibraryByAccountTypeAbstract
{

    private Student $student;
    public function __construct(Student $student)
    {
        $this->student = $student;
    }


    public function getMyAllowedQuestionLibraryQuery(){

        //here get my school id
        $studentClass = new StudentClass();
        $schoolStudent = $studentClass->getMyActiveSchoolStudent($this->student);
        /*$schoolStudent = SchoolStudent::where('student_id',$this->student->id)
            ->active()->first();*/
    	if(is_null($schoolStudent)){
            throw new ErrorMsgException('you should belong to school to use this');
        }
        $mySchoolId = $schoolStudent->school_id;

        //here get the educators ids
        $myEducatorStudents = $studentClass->getMyActiveEducatorStudent($this->student);
        $myEducatorIds = $myEducatorStudents->pluck('educator_id')->toArray();
        /*$myEducatorIds = EducatorStudent::where('student_id',$this->student->id)
            ->active()->pluck('educator_id')->toArray();*/

        $questionBankQuery = LibraryQuestion::query();


        $questionBankQuery->myAllowedAsStudent($mySchoolId,$myEducatorIds);


        return $questionBankQuery;

    }


}

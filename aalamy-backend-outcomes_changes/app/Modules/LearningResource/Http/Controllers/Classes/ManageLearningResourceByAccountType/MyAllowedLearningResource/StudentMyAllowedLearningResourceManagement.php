<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyAllowedLearningResource;
use Modules\LearningResource\Models\LearningResource;
use Modules\User\Http\Controllers\Classes\StudentClass;
use Modules\User\Models\School;
use Modules\User\Models\Student;

class StudentMyAllowedLearningResourceManagement extends BaseManageMyAllowedLearningResourceByAccountTypeAbstract
{

    private Student $student;

    private $mySchoolId;
    private $myEducatorIds;
    public $accessType;

    public function __construct(Student $student)
    {
        $this->student = $student;

        //here get my school id
        $studentClass = new StudentClass();
        $schoolStudent = $studentClass->getMyActiveSchoolStudent($this->student);
        $this->mySchoolId = !is_null($schoolStudent)?$schoolStudent->school_id:-1;
//        $this->mySchoolId = $schoolStudent->school_id;

        //here get the educators ids
        $myEducatorStudents = $studentClass->getMyActiveEducatorStudent($this->student);
        $this->myEducatorIds = $myEducatorStudents->pluck('educator_id')->toArray();


    }

    public function getMyAllowedLearningResourceQuery(){
        $questionLibraryQuery = LearningResource::query();
        $questionLibraryQuery->myAllowedAsStudent(
            $this->mySchoolId,$this->myEducatorIds
        );

        return $questionLibraryQuery;

    }

}

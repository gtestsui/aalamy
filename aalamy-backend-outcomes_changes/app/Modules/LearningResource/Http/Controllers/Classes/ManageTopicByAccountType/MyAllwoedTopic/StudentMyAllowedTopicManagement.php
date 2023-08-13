<?php

namespace Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyAllwoedTopic;

use App\Http\Controllers\Classes\ApplicationModules;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicConstants;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\StudentClass;
use Modules\User\Models\Student;

class StudentMyAllowedTopicManagement extends BaseManageMyAllowedTopicByAccountTypeAbstract
{

    private Student $student;
    private $mySchoolId;
    private $myEducatorIds;
    public $accessType;

    public function __construct(Student $student)
    {

        $this->accessType = TopicConstants::READ_ACCESS_TYPE;

        $this->student = $student;

        //here get my school id
        $studentClass = new StudentClass();
        $schoolStudent = $studentClass->getMyActiveSchoolStudent($this->student);
        $this->mySchoolId = !is_null($schoolStudent)?$schoolStudent->school_id:-1;

        //here get the educators ids
        $myEducatorStudents = $studentClass->getMyActiveEducatorStudent($this->student);
        $this->myEducatorIds = $myEducatorStudents->pluck('educator_id')->toArray();


    }

    public function getMyAllowedTopicReadQuery(){

        $topicQuery = Topic::query();

        $topicQuery->myAllowedAsStudent(
            $this->mySchoolId,$this->myEducatorIds
        );

        return $topicQuery;
    }

    public function checkCanAddInsideTopic(Topic $topic){

        return false;

    }



}

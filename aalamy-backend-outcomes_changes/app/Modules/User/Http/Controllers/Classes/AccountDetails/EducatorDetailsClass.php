<?php


namespace Modules\User\Http\Controllers\Classes\AccountDetails;


use App\Modules\User\Http\Resources\EducatorResource;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\EducatorAssignment;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\EducatorClassManagement;
use Modules\Level\Http\Controllers\Classes\ManageLevel\EducatorLevel;
use Modules\Level\Http\Controllers\Classes\ManageSubject\EducatorSubject;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\EducatorQuestionManagement;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\EducatorRosterClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class EducatorDetailsClass implements EducatorAndSchoolDetailsInterface
{

    private Educator $educator;
    private StudentEducatorClass $studentEducatorClass;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
        $this->studentEducatorClass = new StudentEducatorClass($this->educator);
    }

    public function getDetails():array
    {
        return [
          'educator' => new EducatorResource($this->educator->load('User')),
          'students_count' => $this->myStudentsCount(),
          'teachers_count' => $this->myTeachersCount(),
          'student_parent_count' => $this->myStudentParentsCount(),
          'assignments_count' => $this->myAssignmentsCount(),
          'subjects_count' => $this->mySubjectsCount(),
          'rosters_count' => $this->myRostersCount(),
          'levels_count' => $this->myLevelsCount(),
          'classes_count' => $this->myClassesCount(),
          'questions_bank_count' => $this->myQuestionsBankCount(),

        ];
    }

    public function myStudentsCount():int
    {
        $count = $this->studentEducatorClass->myStudentsQuery()->count();
        return $count;
    }

    public function myTeachersCount():int
    {
        $count = Teacher::where('user_id',$this->educator->user_id)->active()->count();
        return $count;
    }

    public function myStudentParentsCount():int
    {
        $count = $this->studentEducatorClass->myStudentParentsQuery()->count();
        return  $count;
    }

    public function myAssignmentsCount():int
    {
        $schoolAssignment = new EducatorAssignment($this->educator);
        $count = $schoolAssignment->myAssignmentsQuery()->count();
        return  $count;
    }

    public function mySubjectsCount():int
    {
        $educatorSubjectClass = new EducatorSubject($this->educator);
        $count = $educatorSubjectClass->mySubjectsQuery()->count();
        return  $count;

    }

    public function myRostersCount():int
    {
        $educatorRosterClass = new EducatorRosterClass($this->educator);
        $count = $educatorRosterClass->myRostersQuery()->count();
        return  $count;
    }

    public function myLevelsCount():int
    {
        $educatorRosterClass = new EducatorLevel($this->educator);
        $count = $educatorRosterClass->myLevelsQuery()->count();
        return  $count;
    }

    public function myClassesCount():int
    {
        $educatorClassClass = new EducatorClassManagement($this->educator);
        $count = $educatorClassClass->myClassesQuery()->count();
        return  $count;
    }

    public function myQuestionsBankCount():int
    {
        $educatorClassClass = new EducatorQuestionManagement($this->educator);
        $count = $educatorClassClass->getMyQuestionBankQuery()->count();
        return  $count;
    }

}

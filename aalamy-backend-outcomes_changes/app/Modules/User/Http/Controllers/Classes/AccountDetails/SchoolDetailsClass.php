<?php


namespace Modules\User\Http\Controllers\Classes\AccountDetails;


use App\Modules\User\Http\Resources\SchoolResource;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\SchoolAssignment;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\SchoolClassManagement;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\Level\Http\Controllers\Classes\ManageSubject\SchoolSubject;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\SchoolQuestionManagement;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\SchoolRosterClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class SchoolDetailsClass implements EducatorAndSchoolDetailsInterface
{

    private School $school;
    private StudentSchoolClass $studentSchoolClass;
    public function __construct(School $school)
    {
        $this->school = $school;
        $this->studentSchoolClass = new StudentSchoolClass($this->school);
    }

    public function getDetails():array
    {
        return [
          'school' => new SchoolResource($this->school),
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
        $count = $this->studentSchoolClass->myStudentsQuery()->count();
        return $count;
    }

    public function myTeachersCount():int
    {
        $count = Teacher::where('school_id',$this->school->id)->active()->count();
        return $count;
    }

    public function myStudentParentsCount():int
    {
        $count = $this->studentSchoolClass->myStudentParentsQuery()->count();
        return  $count;
    }

    public function myAssignmentsCount():int
    {
        $schoolAssignment = new SchoolAssignment($this->school);
        $count = $schoolAssignment->myAssignmentsQuery()->count();
        return  $count;
    }

    public function mySubjectsCount():int
    {
        $schoolSubjectClass = new SchoolSubject($this->school);
        $count = $schoolSubjectClass->mySubjectsQuery()->count();
        return  $count;

    }

    public function myRostersCount():int
    {
        $schoolRosterClass = new SchoolRosterClass($this->school);
        $count = $schoolRosterClass->myRostersQuery()->count();
        return  $count;
    }

    public function myLevelsCount():int
    {
        $educatorRosterClass = new SchoolLevel($this->school);
        $count = $educatorRosterClass->myLevelsQuery()->count();
        return  $count;
    }

    public function myClassesCount():int
    {
        $educatorClassClass = new SchoolClassManagement($this->school);
        $count = $educatorClassClass->myClassesQuery()->count();
        return  $count;
    }

    public function myQuestionsBankCount():int
    {
        $educatorClassClass = new SchoolQuestionManagement($this->school);
        $count = $educatorClassClass->getMyQuestionBankQuery()->count();
        return  $count;
    }


}

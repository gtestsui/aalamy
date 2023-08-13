<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRosterStudent;


use Carbon\Carbon;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\Roster;
use Modules\Roster\Models\RosterStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\Student;

class EducatorRosterStudentClass implements ManageRosterStudent
{
//
//    private Educator $educator;
//    public function __construct(Educator $educator)
//    {
//        $this->educator = $educator;
//    }
//
//
//    /**
//     * @return EducatorStudent
//     */
//    public function getOrCreateStudentFromMyStudent(Student $student){
//        //get or create student from my student
//        $educatorStudentClass = new StudentEducatorClass($this->educator);
//        $myStudent = $educatorStudentClass->myStudentByStudentId($student->id);
//        if(is_null($myStudent))
//            $myStudent = EducatorStudent::create([
//                'educator_id' =>  $this->educator,
//                'student_id' =>   $student->id,
//                'start_date' =>   Carbon::now(),
//            ]);
//        return $myStudent;
//    }
//
//    /**
//     * @return ClassStudent
//     */
//    public function getOrCreateStudentFromMyClassStudent(Student $student,ClassInfo $classInfo){
//        //get or create student from my classStudent
//        $classStudent = ClassStudent::where('class_id',$classInfo->class_id)
//            ->where('student_id',$student->id)
//            ->first();
//        if(is_null($classStudent))
//            $classStudent = ClassStudent::create([
//                'class_id'   =>  $classInfo->class_id,
//                'student_id' =>  $student->id,
//                'educator_id' => $this->educator,
//            ]);
//        return $classStudent;
//    }
//
//    /**
//     * @return bool
//     */
//    public function getOrCreateStudentFromMyRosterStudent(ClassStudent $classStudent,Roster $roster){
//        //get or create student from my RosterStudent
//        $rosterStudent = RosterStudent::where('class_student_id',$classStudent->id)
//            ->where('roster_id',$roster->id)->first();
//        //this var to check if the student enrolled just now
//        $foundInRosterStatus = true;
//
//        if(is_null($rosterStudent)){
//            $rosterStudent = RosterStudent::create([
//                'class_student_id' =>  $classStudent->id,
//                'roster_id' =>  $roster->id,
//            ]);
//            $foundInRosterStatus = false;
//
//        }
//        return  $foundInRosterStatus;
//    }
//
//


}

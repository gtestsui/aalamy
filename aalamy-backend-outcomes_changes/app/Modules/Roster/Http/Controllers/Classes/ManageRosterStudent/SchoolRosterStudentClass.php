<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRosterStudent;


use App\Exceptions\ErrorMsgException;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\Student;

class SchoolRosterStudentClass implements ManageRosterStudent
{


    public function getRosterStudentByRosterId(){

    }

    /*public function enrollStudentToRoster(Student $student,ClassInfo $classInfo , Roster $roster){
        $schoolStudentClass = new StudentSchoolClass($classInfo->School);
        $myStudent = $schoolStudentClass->myStudentByStudentId($student->id);
        if(is_null($myStudent))
            throw new ErrorMsgException(transMsg('student_doesnt_belongs_to_roster_school','Roster'));



        $classStudent = ClassStudent::where('class_id',$classInfo->class_id)
            ->where('student_id',$student->id)
            ->first();
        if(is_null($classStudent))
            $classStudent = ClassStudent::create([
                'class_id'   =>  $classInfo->class_id,
                'student_id' =>  $student->id,
                'educator_id' => $this->educator->id,
            ]);

        $rosterStudent = RosterStudent::whereIn('class_student_id',$classStudent->id)
            ->where('roster_id',$roster->id)->first();
        if(is_null($rosterStudent))
            $rosterStudent = RosterStudent::create([
                'class_student_id' =>  $classStudent->id,
                'roster_id' =>  $roster->id,
            ]);


    }
    */
}

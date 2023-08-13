<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRosterInvitation;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\ClassModule\Models\ClassStudent;
use Modules\EducatorStudentRequest\Http\Controllers\Classes\EducatorStudentRequestServices;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentPageServices;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\User;

class SchoolRosterInvitationClass extends BaseManageRosterEnroll
{

    private School $school;
    /**
     * instead of constructor
     * when the educator send link to his student and the student click on the link(or insert roster code anyway)
     * @param $userId
     * @return static
     */
    public static function createByCode($userId){
        $instance = new static();
        $instance->school = School::where('user_id',$userId)->first();
        return $instance;
    }



    public function enroll($roster,$studentId){
        $schoolUser = User::findOrFail($this->school->user_id);
        $studentCountModuleClass = StudentCountModuleClass::createByOther($schoolUser,$this->school);
        $studentCountModuleClass->checkWithCustomizedErrorForStudent();

        //check if the student belongs to my student or create
        $educatorStudent = $this->checkIfStudentBelongsToSchoolOrCreate(
            $studentId
        );

        //check if student belongs to my class or create
        $classStudent = $this->checkIfStudentBelongsToClassOrCreate(
            $studentId,$roster->ClassInfo->ClassModel->id
        );

        //check if student belongs to my roster or create
        $rosterStudent = $this->checkIfStudentBelongsToRosterOrCreate(
            $classStudent,$roster
        );


        RosterAssignmentStudentPageServices::addDefinedStudentPages($roster,$studentId);

    }


    /**
     * @return SchoolStudent
     */
    public  function checkIfStudentBelongsToSchoolOrCreate($studentId){
        $studentSchoolClass = new StudentSchoolClass( $this->school);
        $schoolStudent = $studentSchoolClass->myStudentByStudentId($studentId);

        if(is_null($schoolStudent))
            $schoolStudent = SchoolStudent::create([
                'student_id' => $studentId,
                'school_id' => $this->school->id,
                'start_date' => Carbon::now(),
            ]);
        return $schoolStudent;
    }

    /**
     * @return ClassStudent
     */
    public function checkIfStudentBelongsToClassOrCreate($studentId,$classId){
        $classStudent = ClassStudent::where('student_id',$studentId)
            ->where('class_id',$classId)
            ->active()
//            ->whereDate('study_year',Carbon::now())
            ->first();
        if(is_null($classStudent)){
            $classStudent = ClassStudent::create([
                'student_id'=> $studentId,
                'class_id'=> $classId,
                'school_id'=> $this->school->id,
                'study_year'=> Carbon::now(),
            ]);
        }
        return  $classStudent;
    }

    /**
     * @return RosterStudent
     */
    public function checkIfStudentBelongsToRosterOrCreate($classStudent,$roster){
        $rosterStudent = RosterStudent::where('roster_id' , $roster->id)
            ->where('class_student_id' , $classStudent->id)->first();
        if(is_null($rosterStudent))
            $rosterStudent = RosterStudent::create([
                'roster_id' => $roster->id,
                'class_student_id' => $classStudent->id,
            ]);
        return  $rosterStudent;
    }


}

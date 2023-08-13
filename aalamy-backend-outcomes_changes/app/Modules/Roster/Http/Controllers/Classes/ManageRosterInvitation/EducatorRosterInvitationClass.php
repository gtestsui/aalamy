<?php


namespace Modules\Roster\Http\Controllers\Classes\ManageRosterInvitation;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Models\ClassStudent;
use Modules\EducatorStudentRequest\Http\Controllers\Classes\EducatorStudentRequestServices;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentPageServices;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\User;

class EducatorRosterInvitationClass extends BaseManageRosterEnroll
{

    private Educator $educator;
    /**
     * instead of constructor
     * when the educator send link to his student and the student click on the link(or insert roster code anyway)
     * @param $userId
     * @return static
     */
    public static function createByCode($userId){
        $instance = new static();
        $instance->educator = Educator::where('user_id',$userId)->first();
        return $instance;
    }

    /**
     * instead of constructor
     * when educator send request to student to enroll to his roster
     * @param Educator $educator
     * @return static
     */
    public static function createByRequest(Educator $educator){
        $instance = new static();
        $instance->educator = $educator;
        return $instance;
    }

    public function enroll($roster,$studentId){
        $educatorUser = User::findOrFail($this->educator->user_id);
        $studentCountModuleClass = StudentCountModuleClass::createByOther($educatorUser,$this->educator);
        $studentCountModuleClass->checkWithCustomizedErrorForStudent();


        //check if the student belongs to my student or create
        $educatorStudent = $this->checkIfStudentBelongsToEducatorOrCreate(
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


//        RosterAssignmentStudentPageServices::addDefinedStudentPages($roster->id,$studentId);

    }


    /**
     * @return EducatorStudent
     */
    public  function checkIfStudentBelongsToEducatorOrCreate($studentId){
        $studentEducator = new StudentEducatorClass( $this->educator);
        $educatorStudent = $studentEducator->myStudentByStudentId($studentId);

        if(is_null($educatorStudent))
            $educatorStudent = EducatorStudent::create([
                'student_id' => $studentId,
                'educator_id' =>  $this->educator->id,
                'start_date' => Carbon::now(),
            ]);
        return $educatorStudent;
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
            $classStudent = ClassServices::addStudentToClass(
                'educator',$this->educator,$studentId,$classId
            );

//            $classStudent = ClassStudent::create([
//                'student_id'=> $studentId,
//                'class_id'=> $classId,
//                'educator_id'=>  $this->educator->id,
//                'study_year'=> Carbon::now(),
//            ]);
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
            $rosterStudent = RosterServices::addStudentToRoster($roster->id,$classStudent->id);

//        $rosterStudent = RosterStudent::create([
//                'roster_id' => $roster->id,
//                'class_student_id' => $classStudent->id,
//            ]);

        RosterAssignmentStudentPageServices::addDefinedStudentPages($roster->id,$classStudent->student_id);

        return  $rosterStudent;
    }


}

<?php


namespace Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints;



use App\Exceptions\ErrorUnAuthorizationException;
use Modules\SchoolInvitation\Models\SchoolStudentRequest;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\ImportStudentFromExcelModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Teacher;
use Modules\User\Models\School;
use Modules\User\Models\User;

class StudentPermissionClass extends BasePermissionConstraintsClass
{

    protected $name = 'student';
    protected $actions = ['create',
        'send_request_to',
        'approve_or_reject_request_from',
        'delete',
        'import'
        ];
    public function __construct(Teacher $teacher/*,$action*/)
    {
        $this->teacher = $teacher;

        $this->setSchool();

    }



    private function setSchool(){
        $this->school = School::findOrFail($this->teacher->school_id);
    }


    public function checkCreate(){
        $schoolUser = User::findOrFail($this->school->user_id);
        $studentCountModuleClass = StudentCountModuleClass::createByOther($schoolUser,$this->school);
        $studentCountModuleClass->check();

    }

    public function checkSendRequest(){
        $schoolUser = User::findOrFail($this->school->user_id);
        $studentCountModuleClass = StudentCountModuleClass::createByOther($schoolUser,$this->school);
        $studentCountModuleClass->check();
    }



    public function checkDelete(SchoolStudent $schoolStudent){

        $this->check($schoolStudent);

    }


    public function checkApproveOrRejectRequest(SchoolStudentRequest $schoolStudentRequest,$status){
        if($status == config('SchoolInvitation.panel.student_request_statuses.approved')){
            $schoolUser = User::findOrFail($this->school->user_id);
            $studentCountModuleClass = StudentCountModuleClass::createByOther($schoolUser,$this->school);
            $studentCountModuleClass->check();
        }

        if($this->school->id != $schoolStudentRequest->school_id)
            throw new ErrorUnAuthorizationException();
    }

    public function checkImport(){
        $schoolUser = User::findOrFail($this->school->user_id);
        $importStudentFromExcelClass = ImportStudentFromExcelModuleClass::createByOther($schoolUser,$this->school);
        $importStudentFromExcelClass->check();
    }

//
    private function check(SchoolStudent $schoolStudent){
        if($this->school->id != $schoolStudent->school_id)
            throw new ErrorUnAuthorizationException();
    }



}

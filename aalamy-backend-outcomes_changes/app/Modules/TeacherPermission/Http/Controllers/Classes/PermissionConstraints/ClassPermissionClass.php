<?php


namespace Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints;



use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\Level\Http\Controllers\Classes\ManageLevel\TeacherLevel;
use Modules\Level\Models\Level;
use Modules\Level\Models\LevelSubject;
use Modules\SubscriptionPlan\Models\Module;
use Modules\TeacherPermission\Models\Permission;
use Modules\TeacherPermission\Models\PermissionTeacher;
use Modules\User\Http\Controllers\Classes\AccountDetails\SchoolDetailsClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;
use Modules\User\Models\School;
use Modules\User\Models\User;

class ClassPermissionClass extends BasePermissionConstraintsClass
{

    protected $name = 'class';
    protected $actions = ['create','update','delete'];
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;

        $this->setSchool();

    }



    private function setSchool(){
        $this->school = School::findOrFail($this->teacher->school_id);
    }


    //check if he can create the item
    public function checkCreate($levelId){
        $teacherLevelClass =new TeacherLevel($this->teacher);
        $level = $teacherLevelClass->myLevelsById($levelId);
        $this->check($level);
    }

    //check if he can update the item
    public function checkUpdate($levelId){
        $this->checkCreate($levelId);
    }

    //check if he can delete the item
    public function checkDelete($levelId){
        $this->checkCreate($levelId);
    }

    private function check(Level $level){
        if(is_null($level))
            throw new ErrorUnAuthorizationException();
    }



}

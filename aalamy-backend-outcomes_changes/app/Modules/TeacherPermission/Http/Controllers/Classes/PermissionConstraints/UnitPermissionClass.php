<?php


namespace Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints;



use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
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

class UnitPermissionClass extends BasePermissionConstraintsClass
{

    protected $name = 'unit';
    protected $actions = ['create','update','delete'];
    public function __construct(Teacher $teacher/*,$action*/)
    {
        $this->teacher = $teacher;

        $this->setSchool();

    }



//    /**
//     * @param mixed $action
//     * @return static
//     * @throws ErrorUnAuthorizationException
//     * @throws ErrorMsgException
//     */
//    public function checkIfHavePermission($action){
//
//        if(!in_array($action,$this->actions))
//            throw new ErrorMsgException('invalid permission action');
//
//        $permissionName = $action.'_'.$this->name;
////        $this->permission = Permission::where('num',configFromModule('panel.permissions_num.'.$permissionName,ApplicationModules::TEACHER_PERMISSION_MODULE_NAME))
//        $this->permission = Permission::where('name',$permissionName)
//            ->firstOrFail();
//
//        if(!$this->havePermission){
//            $this->setPermissionTeacherOrThrowUnAuthorized( $this->permission,$this->teacher->id);
//            $this->havePermission = true;
//        }
//        return $this;
//    }
//
//    /**
//     * @param array $actions
//     * @return static
//     * @throws ErrorUnAuthorizationException
//     * @throws ErrorMsgException
//     */
//    public function checkIfHaveOneOfThisPermissions($actions){
//        $permissionsNames = [];
//        foreach ($actions as $action){
//            if(!in_array($action,$this->actions))
//                throw new ErrorMsgException('invalid permission action');
//            $permissionsNames[] = $action.'_'.$this->name;
//        }
//        $permissionIds = Permission::whereIn('name',$permissionsNames)->pluck('id')->toArray();
//        $teacherPermissions = PermissionTeacher::where('teacher_id',$this->teacher->id)
//            ->whereIn('permission_id',$permissionIds)
//            ->get();
//        if(!count($teacherPermissions))
//            throw new ErrorUnAuthorizationException();
//
//        return $this;
//
//    }



    private function setSchool(){
        $this->school = School::findOrFail($this->teacher->school_id);
    }


    public function checkCreate(LevelSubject $levelSubject){
        $level = Level::find($levelSubject->level_id);
        $this->check($level);
    }

    public function checkUpdate($levelSubjectId){
        $levelSubject = LevelSubject::find($levelSubjectId);
        $level = Level::find($levelSubject->level_id);
        $this->check($level);

    }

    public function checkDelete($levelSubjectId){
        $levelSubject = LevelSubject::find($levelSubjectId);
        $level = Level::find($levelSubject->level_id);
        $this->check($level);

    }

    private function check(Level $level){
        if($this->school->user_id != $level->user_id)
            throw new ErrorUnAuthorizationException();
    }



}

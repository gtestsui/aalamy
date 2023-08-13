<?php


namespace Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints;



use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\SubscriptionPlan\Http\Controllers\Classes\UserSubscribeClass;
use Modules\SubscriptionPlan\Models\Module;
use Modules\SubscriptionPlan\Models\SubscriptionPlanModule;
use Modules\TeacherPermission\Models\Permission;
use Modules\TeacherPermission\Models\PermissionTeacher;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

abstract class BasePermissionConstraintsClass
{

    protected Teacher $teacher;
    protected School $school;
    protected Permission $permission;
    protected bool $havePermission=false;

    protected PermissionTeacher $permissionTeacher;
    protected User $user;
    protected  $userSubscribe;


    /**
     * check if th teacher has been linked to this permission before
     * @param mixed $action
     * @return static
     * @throws ErrorUnAuthorizationException
     * @throws ErrorMsgException
     */
    public function checkIfHavePermission($action){

        if(!in_array($action,$this->actions))
            throw new ErrorMsgException('invalid permission action');

        $permissionName = $this->getPermissionName($action);
//        $this->permission = Permission::where('num',configFromModule('panel.permissions_num.'.$permissionName,ApplicationModules::TEACHER_PERMISSION_MODULE_NAME))
        $this->permission = Permission::where('name',$permissionName)
            ->firstOrFail();

        if(!$this->havePermission){
            $this->setPermissionTeacherOrThrowUnAuthorized( $this->permission,$this->teacher->id);
            $this->havePermission = true;
        }
        return $this;
    }

    /**
     * check if th teacher has been linked to one of these permissions before
     * @param array $actions
     * @return static
     * @throws ErrorUnAuthorizationException
     * @throws ErrorMsgException
     */
    public function checkIfHaveOneOfThisPermissions($actions){
        $permissionsNames = [];
        foreach ($actions as $action){
            if(!in_array($action,$this->actions))
                throw new ErrorMsgException('invalid permission action');
            $permissionsNames[] = $this->getPermissionName($action);
        }

        $teacherPermissions = PermissionTeacher::where('teacher_id',$this->teacher->id)
            ->whereHas('Permission',function ($query)use ($permissionsNames){
                return $query->whereIn('name',$permissionsNames);
            })
            ->get();
        if(!count($teacherPermissions))
            throw new ErrorUnAuthorizationException();

        return $this;

    }

    /**
     * @return string
     */
    protected function getPermissionName($action){
        return $action.'_'.$this->name;
    }


    /**
     * check if the action its string then check if you have permission by it
     * else is it array then check if you have permission by one of the at least
     * @param array|mixed $action
     * @return bool
     */
    public function isHavePermission($action){
        try {

            if(is_array($action))
                $this->checkIfHaveOneOfThisPermissions($action);
            else
                $this->checkIfHavePermission($action);
            return true;
        }catch (\Exception $e){
            return false;
        }

    }

    /**
     * this function will initialize the module we have targeted it
     * @throws ErrorUnAuthorizationException
     */
    protected function setPermissionTeacherOrThrowUnAuthorized($permission,$teacherId){
        $permissionTeacher = PermissionTeacher::where('teacher_id',$teacherId)
            ->where('permission_id',$permission->id)
            ->first();

        if(is_null($permissionTeacher))
            throw new ErrorUnAuthorizationException();

        $this->permissionTeacher = $permissionTeacher;

    }

}

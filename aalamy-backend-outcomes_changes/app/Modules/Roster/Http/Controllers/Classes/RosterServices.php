<?php


namespace Modules\Roster\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\ManageRosterInterface;
use Modules\Roster\Models\Roster;
use Modules\Roster\Models\RosterStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\User;

class RosterServices
{


    /*public static function checkRosterAuthorization(ClassInfo $classInfo,User $user,$action,$teacherId=null){
        if(isset($teacherId)){
            if(TeacherHasPermission($action,$model:'Roster'))
                dd('s');
            else($classInfo->teacher_id != $teacherId)
                throw new ErrorUnAuthorizationException();
        }else{
            if($user->{ucfirst($user->account_type)}->id != $classInfo->{$user->account_type.'_id'})
                throw new ErrorUnAuthorizationException();
        }
    } */

    /**
     * check if the classInfo belongs to the user
     */
    public static function checkCreateRosterAuthorization(ClassInfo $classInfo,User $user,$teacherId=null){
        if(isset($teacherId)){
            if($classInfo->teacher_id != $teacherId)
                throw new ErrorUnAuthorizationException();
        }else{
        	$user->load(ucfirst($user->account_type));
            if($user->{ucfirst($user->account_type)}->id != $classInfo->{$user->account_type.'_id'})
                throw new ErrorUnAuthorizationException();
        }
    }

    public static function checkUpdateRosterAuthorization(ClassInfo $classInfo,User $user,$teacherId=null){
        Self::checkCreateRosterAuthorization($classInfo,$user,$teacherId);
    }

    public static function checkDestroyRosterAuthorization(ClassInfo $classInfo,User $user,$teacherId=null){
        Self::checkCreateRosterAuthorization($classInfo,$user,$teacherId);
    }

    public static function checkAddStudentToRosterAuthorization(Roster $roster,User $user,$teacherId=null){
        $classInfo = ClassInfo::findOrFail($roster->class_info_id);

        Self::checkCreateRosterAuthorization($classInfo,$user,$teacherId);
    }

    public static function checkGetStudentsFromRosterAuthorization(Roster $roster,User $user,$teacherId=null){
        $classInfo = ClassInfo::findOrFail($roster->class_info_id);

        Self::checkCreateRosterAuthorization($classInfo,$user,$teacherId);
    }

    public static function checkDeleteStudentFromRosterAuthorization(RosterStudent $rosterStudent,User $user,$teacherId=null){
        $roster = Roster::find($rosterStudent->roster_id);
        $classInfo = ClassInfo::findOrFail($roster->class_info_id);

        Self::checkCreateRosterAuthorization($classInfo,$user,$teacherId);
    }

    /**
     * we have used $roster->ClassInfo because in some places we will load the relation
     * before send the parameter to this function to reduce the query num
     */
    public static function checkUseRosterAuthorization(Roster $roster,User $user,$teacherId=null){
        $classInfo = $roster->load('ClassInfo')->ClassInfo;
        if(is_null($classInfo))
            throw new ErrorUnAuthorizationException();
//        $classInfo = ClassInfo::findOrFail($roster->class_info_id);

        Self::checkCreateRosterAuthorization($classInfo,$user,$teacherId);
    }


    public static function generateRosterCode():string
    {
//        $code = UserServices::generateRandomString(6);
        $code = ConfirmationAccountServices::generateRandomString(6);
        $roster = Roster::where('code',$code)->first();
        if(!is_null($roster))
            return  Self::generateRosterCode();
        return $code;
    }


    /**
     * @param Collection $classStudents of ClassStudent
     */
    public static function prepareRosterStudentArrayForCreate(Collection $classStudents,$rosterId){
        //get found classStudent then exclude them to avoid conflict in database
        $classStudentIds = $classStudents->pluck('id')->toArray();
        $foundClassStudentIds = RosterStudent::whereIn('class_student_id',$classStudentIds)
            ->where('roster_id',$rosterId)
            ->pluck('class_student_id')->toArray();

        //prepare array for add students to roster
        $prepareRosterStudentArrayForCreate = [];
        foreach ($classStudents as $key=>$classStudent){
            if(!in_array($classStudent->id,$foundClassStudentIds)){
                $prepareRosterStudentArrayForCreate[$key]['class_student_id'] = $classStudent->id;
                $prepareRosterStudentArrayForCreate[$key]['roster_id'] = $rosterId;
                $prepareRosterStudentArrayForCreate[$key]['created_at'] = Carbon::now();
            }
        }
        return $prepareRosterStudentArrayForCreate;
    }


    /**
     * @return ManageRosterInterface
     */
//    public static function createManageRosterClassByType($accountType,User $user,$teacherId=null):ManageRosterInterface
//    {
//        $ds = DIRECTORY_SEPARATOR;
//
//        //we made this to check on teacher type
//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
//
//        $eventClassNameByType = "{$accountType}RosterClass";
//        $eventClassPathByType = "Modules{$ds}Roster{$ds}Http{$ds}Controllers{$ds}Classes{$ds}ManageRoster{$ds}{$eventClassNameByType}";
//        if(class_exists($eventClassPathByType)){
//            $eventClassByType = new $eventClassPathByType($accountObject);
//            return $eventClassByType;
//        }
//        throw new ErrorMsgException('trying to declare invalid class type ');
//    }

    public static function addStudentToRoster($rosterId,$classStudentId){
        return  RosterStudent::create([
            'roster_id' => $rosterId,
            'class_student_id' => $classStudentId,
        ]);
    }

}

<?php

namespace Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\User;

abstract class RosterAssignmentPageManagementFactory extends AbstractManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'educator' => EducatorRosterAssignmentPage::class,
        'school' => SchoolRosterAssignmentPage::class,
        'teacher' => TeacherRosterAssignmentPage::class,
        'student' => StudentRosterAssignmentPage::class,
        'parent' => ParentStudentRosterAssignmentPage::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function create(User $user,$teacherId=null):BaseRosterAssignmentPageAbstract
    {
        //we made this to check on teacher type
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$accountType];
        if(class_exists($classPath)){
            if($accountType == 'parent'){
//                $student = Student::findOrFail(request()->student_id);
                return new $classPath($accountObject/*,$student*/);
            }
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }



    public static function createByAccountTypeAndObject($accountType,$accountObject):BaseRosterAssignmentPageAbstract
    {
        //we made this to check on teacher type
//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$teacherId);
//        $accountType = strtolower($accountType);
        if(!key_exists($accountType,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath($accountObject);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


}

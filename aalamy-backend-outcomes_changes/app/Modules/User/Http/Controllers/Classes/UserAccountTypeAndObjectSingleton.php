<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use Illuminate\Http\Request;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class UserAccountTypeAndObjectSingleton
{

    public static $accountType = null;
    public static $accountObject = null;
//    public static $ignoredTeacher = false;

    //when send $ignoreTeacher true, so we reload the data and ignore the single tone
    /** call this method to get instance to prevent running new query while get accountObject of the user */
    public static function instance(User $user,$ignoreTeacher=false){
        $teacherId = $ignoreTeacher===true?null:Request()->my_teacher_id;
        if (static::$accountType === null || static::$accountObject === null || $ignoreTeacher){
            if(!is_null($teacherId) && $user->account_type == 'educator'){
                static::$accountType = 'teacher';
                static::$accountObject = Teacher::where('user_id',$user->id)
                    ->findOrFail($teacherId);
            }else{
                static::$accountType = $user->account_type;
                static::$accountObject = $user->{ucfirst($user->account_type)};

            }

//            static::$instance = new static();
        }
//        return static::$instance;



        return [static::$accountType,static::$accountObject];

    }

    /**
     * we should use flush when the user who make the request is not the same user
     * who sent to static::instance function
     * (because when send not the same user who logged in
     * ,maybe we have used the function in another place before and sent the user who logged in
     * then will be cached as singleton ,and we want the info of the new user)
     *
     * reset the values to null
     */
    public static function flush(){
        static::$accountObject = null;
        static::$accountType = null;
    }

    /** protected to prevent cloning */
    protected function __clone(){
    }

    /** protected to prevent instantiation from outside of the class */
    protected function __construct(){
    }
}

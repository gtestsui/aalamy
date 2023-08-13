<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Exceptions\UnVerifiedAccountException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Controllers\Classes\Services\RegisterServices;
use Modules\User\Http\Requests\Educator\StoreEducatorRequest;
use Modules\User\Http\Requests\Educator\UpdateEducatorRequest;
use Modules\User\Http\Requests\Parent\StoreParentRequest;
use Modules\User\Http\Requests\Parent\UpdateParentRequest;
use Modules\User\Http\Requests\School\StoreSchoolRequest;
use Modules\User\Http\Requests\School\UpdateSchoolRequest;
use Modules\User\Http\Requests\Student\StoreStudentRequest;
use Modules\User\Http\Requests\Student\UpdateStudentRequest;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class UserServices
{


    public static function getStoreValidationRequestByType($accountType):FormRequest
    {
        $paths = [
            'student' => StoreStudentRequest::class,
            'educator' => StoreEducatorRequest::class,
//            'teacher' => StoreTeach::class,
            'school' => StoreSchoolRequest::class,
            'parent' => StoreParentRequest::class,
        ];

        if(!key_exists($accountType,$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = $paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath();
        }
        throw new ErrorMsgException('trying to declare invalid class type ');


    }

    public static function getUpdateProfileValidationRequestByType($accountType):FormRequest
    {

        $paths = [
            'student' => UpdateStudentRequest::class,
            'educator' => UpdateEducatorRequest::class,
//            'teacher' => StoreTeach::class,
            'school' => UpdateSchoolRequest::class,
            'parent' => UpdateParentRequest::class,
        ];

        if(!key_exists($accountType,$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = $paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath();
        }
        throw new ErrorMsgException('trying to declare invalid class type ');

    }

    /**
     * @param $accountType
     * @return UserClass|StudentClass|EducatorClass|ParentClass|SchoolClass depends on userAccountType
     * @throws ErrorMsgException
     */
    public static function getObjectFromUserClassChildByType($accountType):UserClass
    {
        $paths = [
            'student' => StudentClass::class,
            'educator' => EducatorClass::class,
            'parent' => ParentClass::class,
            'school' => SchoolClass::class,
        ];

        if(!key_exists($accountType,$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = $paths[$accountType];
        if(class_exists($classPath)){
            return new $classPath();
        }
        throw new ErrorMsgException('trying to declare invalid class type ');


    }


    public static function checkThisTeacherItsMe($userId,Teacher $teacher){
        if($userId != $teacher->user_id)
            throw new ErrorMsgException('you are not the owner of this teacher!');
    }

    public static function checkActiveTeacher(Teacher $teacher){
        $school = School::findOrFail($teacher->school_id);
        if($school->is_active != 1)
            throw new ErrorMsgException(transMsg('not_active_school_account',ApplicationModules::USER_MODULE_NAME));
        if($teacher->is_active != 1)
            throw new ErrorMsgException(transMsg('not_active_account',ApplicationModules::USER_MODULE_NAME));

    }

    public static function checkIsActiveAccount(User $user,$teacherId=null){

        $user->load(ucfirst($user->account_type));
        if($user->{ucfirst($user->account_type)}->is_active != 1)
            throw new ErrorMsgException(transMsg('not_active_account',ApplicationModules::USER_MODULE_NAME));

        if(isset($teacherId)){
            $teacher = Teacher::findOrFail($teacherId);
            Self::checkThisTeacherItsMe($user->id,$teacher);
            Self::checkActiveTeacher($teacher);
            return true;
        }

//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
//        if($accountObject->is_active != 1)
//            throw new ErrorMsgException(transMsg('not_active_account',ApplicationModules::USER_MODULE_NAME));
//
//        if(isset($teacherId)){
//            $user->load('Educator');
//            Self::checkThisTeacherItsMe($user->id,$accountObject);
//            Self::checkActiveTeacher($accountObject);
//            return true;
//        }

    }

    public static function checkIsVerifiedAccount(User $user){
        if($user->verified_status != 1)
            throw new UnVerifiedAccountException();
//            throw new ErrorMsgException(transMsg('not_verified_account',ApplicationModules::USER_MODULE_NAME));
    }

    /**
     * return the service we have used to register in the site(google.email,..)
     * @param null $service
     * @return mixed|string
     */
    public static function getAccountId($service=null){
//        $account_id = isset($request->email)?'email':'phone';
        return isset($service)?$service:'email';
    }

    /**
     * check if the roles in $accountTypes array its valid
     * and the accountTypes in array should be as same userConfig
     * in all_account_types
     */
    public static function checkRoles(User $user,Array $accountTypes){
        $authorize = false;
        foreach ($accountTypes as $accountType){
//            $accountType = strtolower($accountType);
            $accountType = lcfirst($accountType);
//            Self::checkValidAccountType($accountType);
            RegisterServices::checkValidAccountType($accountType);
            if(Self::{"is".ucfirst($accountType)}($user))
                return true;
        }
        throw new ErrorUnAuthorizationException();

    }

    public static function isSuperAdmin(User $user){
//        if($user->account_type != config('User.panel.all_account_types.superAdmin'))
        if(!in_array($user->account_type,config('User.panel.admins')))
            return false;
        return true;
    }

    public static function isParent(User $user){
        if($user->account_type != config('User.panel.all_account_types.parent'))
            return false;
        return true;
    }

    public static function isStudent(User $user){
        if($user->account_type != config('User.panel.all_account_types.student'))
            return false;
        return true;
    }

    public static function isEducator(User $user){
        if($user->account_type != config('User.panel.all_account_types.educator'))
            return false;
        return true;
    }

    //the teacher is educator too
    public static function isTeacher(User $user){
        if($user->account_type != config('User.panel.all_account_types.educator') ||
            !isset(request()->my_teacher_id))
            return false;
        return true;
    }

    public static function isSchool(User $user){
        if($user->account_type != 'school')
            return false;
        return true;
    }


    public static function checkParentEmail(Student $student){
        if(is_null($student->parent_email))
            throw new ErrorMsgException(transMsg('doesnt_have_parent_email',ApplicationModules::USER_MODULE_NAME));

    }

    /**
     * using singleton
     * @return array first element is :
     * @var string $accountType its start with capital letter char
     * @var Mix|Teacher|Educator|School $accountObject
     */
    public static function getAccountTypeAndObject($user,$ignoreTeacherId=false){
        if(isset($ignoreTeacherId))
            return UserAccountTypeAndObjectSingleton::instance($user,$ignoreTeacherId);
        return  UserAccountTypeAndObjectSingleton::instance($user);

    }

    /**
     * when the parent trying to display his student assignments or anything else
     * he should send student_id in the request and this function responsible for
     * initialize object from student
     * using singleton to decrease query count
     * @return Student
     */
    public static function getTargetedStudentByParent($studentId){
        return  TargetStudentByParentSingleton::instance($studentId);

    }


//    public static function createClassStudentManagementClassByType($accountType,User $user,$teacherId=null): ManageStudentInterface
//    {
//        $ds = DIRECTORY_SEPARATOR;
//
//        list($accountType,$accountObject) = Self::getAccountTypeAndObject($accountType,$user,$teacherId);
//
//        $studentManagementClassNameByType = "Student{$accountType}Class";
//        $studentManagementClassPathByType = "Modules{$ds}User{$ds}Http{$ds}Controllers{$ds}Classes{$ds}ManageStudent{$ds}{$studentManagementClassNameByType}";
//        if(class_exists($studentManagementClassPathByType)) {
//            $studentManagementClassByType = new $studentManagementClassPathByType($accountObject);
//            return $studentManagementClassByType;
//        }
//        throw new ErrorMsgException('trying to declare invalid class type ');
//
//    }

//    public static function createAccountDetailsClassByType($accountType,User $user,$teacherId=null)
//    {
//        $ds = DIRECTORY_SEPARATOR;
//
//        list($accountType,$accountObject) = Self::getAccountTypeAndObject($accountType,$user,$teacherId);
//
//        $accountDetailsClassNameByType = "{$accountType}DetailsClass";
//        $accountDetailsClassPathByType = "Modules{$ds}User{$ds}Http{$ds}Controllers{$ds}Classes{$ds}AccountDetails{$ds}{$accountDetailsClassNameByType}";
//        if(class_exists($accountDetailsClassPathByType)) {
//            $accountDetailsClassByType = new $accountDetailsClassPathByType($accountObject);
//            return $accountDetailsClassByType;
//        }
//        throw new ErrorMsgException('trying to declare invalid class type ');
//
//    }

    public static function getUserRelationModelPaths(){
        $relationModelPaths = [
            'student' => Student::class,
            'school' => School::class,
            'parent' => ParentModel::class,
            'educator' => Educator::class,
        ];
        return $relationModelPaths;
    }

    public static function revokeAllTokens(User $user){
        $tokens = $user->load('tokens')->tokens;
        foreach ($tokens as $token){
            $token->revoke();
        }
        return $user->createToken('token')->accessToken;
    }

    public static function  generateUniqueGuide($limit=32)
    {
        return substr(
                base_convert(
                        sha1(
                            uniqid(
                                mt_rand()
                            )
                    ), 16, 36
            ), 0, $limit);
    }

    public static function prepareOnwer(User $user,Request $request){
        $schoolId = null;
        $teacherId = null;
        $educatorId = null;
        if(isset($request->my_teacher_id)){
            $teacher = Teacher::findOrFail($request->my_teacher_id);
            $teacherId = $teacher->id;
            $schoolId = (int)$teacher->school_id;
        }else{
            ${$user->account_type.'Id'} = $user->{ucfirst($user->account_type)}->id;

        }
        return [$schoolId,$teacherId,$educatorId];

    }

    public static function getMyTeacherAccountsSingletone(Educator $educator){
        try {
            return app('myTeacherAccounts');
        }catch (\Exception $e){
            app()->singleton('myTeacherAccounts', function()use ($educator){
                return Teacher::where('user_id',$educator->user_id)
                    ->get();
            });
            return app('myTeacherAccounts');
        }
    }

//    public static function checkShouldRemoveWithoutDeletedItemsScope(){
//        //remove WithOutDeletedItemsScope when the admin request for display deleted items
//        //( ignore the deleted column and return all )
//        //,and we have used this way to return the related relations with this model even its deleted
//        //because sometimes maybe the item belong to another deleted item and if we don't delete
//        //the WithOutDeletedItemsScope will return the relation null
//        if(!is_null(request()->route('soft_delete')) && UserServices::isSuperAdmin(request()->user()))
//            return true;
//        return false;
//    }

    public static function checkShouldAddWithoutDeletedItemsScope(){
//        dd(request()->user());
        //remove WithOutDeletedItemsScope when the admin request for display deleted items
        //( ignore the deleted column and return all )
        //,and we have used this way to return the related relations with this model even its deleted
        //because sometimes maybe the item belong to another deleted item and if we don't delete
        //the WithOutDeletedItemsScope will return the relation null
        $user = Auth::guard('api')->user();
        if(!is_null(request()->route('soft_delete')) && UserServices::isSuperAdmin($user))
            return false;
        return true;
    }


}

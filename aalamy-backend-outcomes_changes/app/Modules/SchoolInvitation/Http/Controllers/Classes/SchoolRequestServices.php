<?php


namespace Modules\SchoolInvitation\Http\Controllers\Classes;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Scopes\DefaultOrderByScope;
use Modules\SchoolInvitation\Http\DTO\SchoolStudentRequestData;
use Modules\SchoolInvitation\Http\DTO\SchoolTeacherRequestData;
use Modules\SchoolInvitation\Models\SchoolStudentRequest;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\StudentCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\TeacherCountModuleByTeachersClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\TeacherCountModuleClass;
use Modules\SubscriptionPlan\Http\Controllers\Classes\UserSubscribeClass;
use Modules\SubscriptionPlan\Models\Module;
use Modules\SubscriptionPlan\Models\SubscriptionPlanModule;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;


class SchoolRequestServices
{

    public static function checkSchoolTeacherRequestApprovalAuthorization(SchoolTeacherRequest $schoolRequest,User $user){

        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,true);
        if($accountType == 'educator'){
            self::checkSchoolTeacherRequestApprovalByEducatorAuthorization($schoolRequest,$user,$accountObject);
        }elseif($accountType == 'school'){
            self::checkSchoolTeacherRequestApprovalBySchoolAuthorization($schoolRequest,$user,$accountObject);
        }
    }

    public static function checkSchoolStudentRequestApprovalAuthorization(SchoolStudentRequest $schoolRequest,User $user,$status){

        if(isset(request()->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $unitPermissionClass = new StudentPermissionClass($teacher);
            $unitPermissionClass->checkIfHavePermission('approve_or_reject_request_from_student')
                ->checkApproveOrRejectRequest($schoolRequest,$status);
            return true;
        }

        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,true);
        if($accountType == 'student'){
            self::checkSchoolStudentRequestApprovalByStudentAuthorization($schoolRequest,$user,$accountObject,$status);
        }elseif($accountType == 'school'){
            self::checkSchoolStudentRequestApprovalBySchoolAuthorization($schoolRequest,$user,$accountObject,$status);
        }
    }

    public static function checkSchoolTeacherRequestApprovalByEducatorAuthorization(SchoolTeacherRequest $schoolRequest,User $user,Educator $educator){

        if($schoolRequest->to != 'educator')
            throw new ErrorUnAuthorizationException();
        if($schoolRequest->educator_id != $educator->id)
            throw new ErrorUnAuthorizationException();

        //to customize the error
        $school = School::with('User')->findOrFail($schoolRequest->school_id);
        $teacherCountModuleClass = TeacherCountModuleClass::createByOther($school->User,$school);
        $teacherCountModuleClass->checkWithCustomizedErrorForTeacher();

    }

    public static function checkSchoolStudentRequestApprovalByStudentAuthorization(SchoolStudentRequest $schoolRequest,User $user,Student $student,$status){

        if($schoolRequest->to != 'student')
            throw new ErrorUnAuthorizationException();
        if($schoolRequest->student_id != $student->id)
            throw new ErrorUnAuthorizationException();

        if($status == config('SchoolInvitation.panel.student_request_statuses.approved')) {
            $school = School::with('User')->findOrFail($schoolRequest->school_id);
            $studentCountModuleClass = StudentCountModuleClass::createByOther($school->User,$school);
            $studentCountModuleClass->checkWithCustomizedErrorForStudent();

        }


    }

    public static function checkSchoolStudentRequestApprovalBySchoolAuthorization(SchoolStudentRequest $schoolRequest,User $user,School $school,$status){

        if($schoolRequest->to != 'school')
            throw new ErrorUnAuthorizationException();
        if($schoolRequest->school_id != $school->id)
            throw new ErrorUnAuthorizationException();

        if($status == config('SchoolInvitation.panel.student_request_statuses.approved')) {
            $studentCountModuleClass = StudentCountModuleClass::createByOwner($user);
            $studentCountModuleClass->check();

        }


    }

    public static function checkSchoolTeacherRequestApprovalBySchoolAuthorization(SchoolTeacherRequest $schoolRequest,User $user,School $school){

        if($schoolRequest->to != 'school')
            throw new ErrorUnAuthorizationException();
        if($schoolRequest->school_id != $school->id)
            throw new ErrorUnAuthorizationException();


//        $teacherCountModuleClass = new TeacherCountModuleClass($user);
        $teacherCountModuleClass = TeacherCountModuleClass::createByOwner($user);
        $teacherCountModuleClass->check();


    }

    public static function checkSchoolRequestDestroyAuthorization($schoolRequest,User $user){
        if(is_null($user->{ucfirst($schoolRequest->from)}))
            throw new ErrorUnAuthorizationException();
        if($user->{ucfirst($schoolRequest->from)}->id != $schoolRequest->{$schoolRequest->from.'_id'})
            throw new ErrorUnAuthorizationException();

    }

    public static function checkSendSchoolTeacherRequestAuthorization(User $user,$educator_id,$school_id){
//        if($user->account_type != 'school' && $user->account_type != 'educator')
//            throw new ErrorUnAuthorizationException();

        if($user->account_type == 'educator'){
            Self::checkFromTeacherToSchoolAuthorization($school_id);
        }else{
            Self::checkFromSchoolToTeacherAuthorization($user,$educator_id);
        }
    }

    public static function checkFromTeacherToSchoolAuthorization($school_id){
        $school = School::findOrFail($school_id);
        if(!$school->allow_teacher_request)
            throw new ErrorMsgException(transMsg('stopped_requests_to_school',ApplicationModules::SCHOOL_INVITATION_MODULE_NAME));
    }

    public static function checkFromSchoolToTeacherAuthorization(User $user,$educator_id){

        $teacherCountModuleClass = TeacherCountModuleClass::createByOwner($user);
        $teacherCountModuleClass->check();

    }

    public static function checkSendSchoolStudentRequestAuthorization(User $user,$student_id,$school_id){

        if(isset(request()->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $studentPermissionClass = new StudentPermissionClass($teacher);
            $studentPermissionClass->checkIfHavePermission('send_request_to')
                ->checkSendRequest();
            return true;
        }


        if($user->account_type != 'student' && $user->account_type != 'school')
            throw new ErrorUnAuthorizationException();

        if($user->account_type == 'student'){
            Self::checkFromStudentToSchoolAuthorization($school_id);
        }else{
            Self::checkFromSchoolToStudentAuthorization($user,$student_id);
        }
    }

    public static function checkFromStudentToSchoolAuthorization($school_id){
        $school = School::findOrFail($school_id);
        if(!$school->allow_student_request)
            throw new ErrorMsgException(transMsg('stopped_requests_to_school',ApplicationModules::SCHOOL_INVITATION_MODULE_NAME));
    }

    public static function checkFromSchoolToStudentAuthorization(User $user,$student_id){
        $studentCountModuleClass = StudentCountModuleClass::createByOwner($user);
        $studentCountModuleClass->check();
    }

    public static function checkSendSchoolTeacherInviteAuthorization(User $user){
        if($user->account_type != 'school')
            throw new ErrorUnAuthorizationException();

//        $teacherCountModuleClass = new TeacherCountModuleClass($user);
        $teacherCountModuleClass = TeacherCountModuleClass::createByOwner($user);
        $teacherCountModuleClass->check();

    }

    public static function RequestIsAvailable($schoolRequest){
        if($schoolRequest->status != 'waiting')
            throw new ErrorMsgException(transMsg('request_in_not_available',ApplicationModules::SCHOOL_INVITATION_MODULE_NAME));
    }

    public static function checkFoundSchoolStudentRequest(SchoolStudentRequestData $requestData){
        $foundRequest = SchoolStudentRequest::where('school_id',$requestData->school_id)
            ->where('student_id',$requestData->student_id)
            ->byStatus(config('SchoolInvitation.panel.student_request_statuses.waiting'))
            ->first();
        if(!is_null($foundRequest))
            throw new ErrorMsgException(transMsg('you_have_been_sent_request_before',ApplicationModules::SCHOOL_INVITATION_MODULE_NAME));

    }

    public static function checkFoundSchoolTeacherRequest(SchoolTeacherRequestData $requestData){
        $foundRequest = SchoolTeacherRequest::where('school_id',$requestData->school_id)
            ->where('educator_id',$requestData->educator_id)
            ->byStatus(config('SchoolInvitation.panel.teacher_request_statuses.waiting'))
            ->first();
        if(!is_null($foundRequest))
            throw new ErrorMsgException(transMsg('you_have_been_sent_request_before',ApplicationModules::SCHOOL_INVITATION_MODULE_NAME));

    }

    public static function checkStudentBelongsToSchool($studentId,$schoolId){
        $schoolStudent = SchoolStudent::where('student_id',$studentId)
            ->where('school_id',$schoolId)
            ->active()->first();
        if(!is_null($schoolStudent))
            throw new ErrorMsgException(transMsg('student_belongs_to_school',ApplicationModules::SCHOOL_INVITATION_MODULE_NAME));
    }

    public static function checkTeacherBelongsToSchool($educatorId,$schoolId){
        $educator = Educator::findOrFail($educatorId);
//        $teacher = Teacher::belongToSchool($educator->user_id,$schoolId)->first();
        $teacher = Teacher::definedEducatorBelongToSchool($educator->user_id,$schoolId)->first();
//        $teacher = Teacher::where('user_id',$educator->user_id)
//            ->where('school_id',$schoolId)
//            ->active()->first();
        if(!is_null($teacher))
            throw new ErrorMsgException(transMsg('educator_belongs_to_school',ApplicationModules::SCHOOL_INVITATION_MODULE_NAME));
    }

    /**
     * @return array|null will
     * @note this function will count the requests and return them separated
     * as requested received sent
     */
    public static function getRequestsCount($query,$pagePagination){
        $requestsCount = [];
        if($pagePagination>1){
            return null;
        }
        //we have stopped the global scope because we won't get the id in the select
        $myRequestsCount = $query->select('status',\DB::raw('Count(status) AS count'))
            ->withoutGlobalScope(DefaultOrderByScope::class)
            ->groupBy('status')
            ->get()
            ->groupBy('status');


        $requestStatuses = config('SchoolInvitation.panel.teacher_request_statuses');
        foreach ($requestStatuses as $requestStatus){
            if(isset($myRequestsCount[$requestStatus]))
                $requestsCount[$requestStatus] = $myRequestsCount[$requestStatus][0]->count;
            else
                $requestsCount[$requestStatus] = 0;
        }
        return !empty($requestsCount)?$requestsCount:null;


    }

}

<?php


namespace Modules\EducatorStudentRequest\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Scopes\DefaultOrderByScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassStudent;
use Modules\EducatorStudentRequest\Models\EducatorRosterStudentRequest;
use Modules\Roster\Http\Controllers\Classes\ManageRosterInvitation\EducatorRosterInvitationClass;
use Modules\Roster\Models\Roster;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentPageServices;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;

class EducatorStudentRequestServices
{

    public static function getMethodNameFromRequestStatus($status){
        $methodNames = [
            config('EducatorStudentRequest.panel.educator_roster_student_request_statuses.approved')
            => 'approve',
            config('EducatorStudentRequest.panel.educator_roster_student_request_statuses.rejected')
            => 'reject'
        ];
        if(!key_exists($status,$methodNames))
            throw new ErrorMsgException('invalid request status');

        return $methodNames[$status];
    }

    public static function approve(EducatorRosterStudentRequest $educatorRosterStudentRequest){
        $educatorRosterStudentRequest->approve();
        $educator = Educator::find($educatorRosterStudentRequest->educator_id);

        $roster = Roster::with('ClassInfo.ClassModel')
            ->find($educatorRosterStudentRequest->roster_id);

        EducatorRosterInvitationClass::createByRequest($educator)
            ->enroll($roster,$educatorRosterStudentRequest->student_id);


//        //check if the student belongs to my student or create
//        $educatorStudent = self::checkIfStudentBelongsToEducatorOrCreate(
//            $educator,$educatorRosterStudentRequest->student_id
//        );
//
//        //check if student belongs to my class or create
//        $roster = Roster::with('ClassInfo.ClassModel')
//            ->find($educatorRosterStudentRequest->roster_id);
//        $classStudent = self::checkIfStudentBelongsToClassOrCreate(
//            $educator,$educatorRosterStudentRequest->student_id,$roster->ClassInfo->ClassModel->id
//        );
//
//        //check if student belongs to my roster or create
//        $rosterStudent = self::checkIfStudentBelongsToRosterOrCreate(
//            $classStudent,$roster
//        );
//
//
//        RosterAssignmentStudentPageServices::addDefinedStudentPages($educatorRosterStudentRequest->roster_id,$educatorRosterStudentRequest->student_id);

    }


    public static function reject(EducatorRosterStudentRequest $educatorRosterStudentRequest,$rejectCause){
        $educatorRosterStudentRequest->reject($rejectCause);
    }

//    /**
//     * @return EducatorStudent
//     */
//    public static function checkIfStudentBelongsToEducatorOrCreate(Educator $educator,$studentId){
//        $studentEducator = new StudentEducatorClass($educator);
//        $educatorStudent = $studentEducator->myStudentByStudentId($studentId);
//
//        if(is_null($educatorStudent))
//            $educatorStudent = EducatorStudent::create([
//                'student_id' => $studentId,
//                'educator_id' => $educator->id,
//                'start_date' => Carbon::now(),
//            ]);
//        return $educatorStudent;
//    }
//
//    /**
//     * @return ClassStudent
//     */
//    public static function checkIfStudentBelongsToClassOrCreate($educator,$studentId,$classId){
//        $classStudent = ClassStudent::where('student_id',$studentId)
//            ->where('class_id',$classId)
//            ->active()
//            ->whereDate('study_year',Carbon::now())
//            ->first();
//        if(is_null($classStudent)){
//            $classStudent = ClassStudent::create([
//                'student_id'=> $studentId,
//                'class_id'=> $classId,
//                'educator_id'=> $educator->id,
//                'study_year'=> Carbon::now(),
//            ]);
//        }
//        return  $classStudent;
//    }
//
//    /**
//     * @return RosterStudent
//     */
//    public static function checkIfStudentBelongsToRosterOrCreate($classStudent,$roster){
//        $rosterStudent = RosterStudent::where('roster_id' , $roster->id)
//            ->where('class_student_id' , $classStudent->id)->first();
//        if(is_null($rosterStudent))
//            $rosterStudent = RosterStudent::create([
//                'roster_id' => $roster->id,
//                'class_student_id' => $classStudent->id,
//            ]);
//        return  $rosterStudent;
//    }



    public static function RequestIsAvailable($educatorStudentRequest){
        if($educatorStudentRequest->status != 'waiting')
            throw new ErrorMsgException(transMsg('request_in_not_available',ApplicationModules::EDUCATOR_STUDENT_REQUEST_MODULE_NAME));
    }


    public static function getRequestsCount($query,$pagePagination){
        $requestsCount = [];
        if($pagePagination>1){
            return null;
        }
        $myRequestsCount = $query->select('status',\DB::raw('Count(status) AS count'))
            ->withoutGlobalScope(DefaultOrderByScope::class)
            ->groupBy('status')
            ->get()
            ->groupBy('status');


        $requestStatuses = config('EducatorStudentRequest.panel.educator_roster_student_request_statuses');
        foreach ($requestStatuses as $requestStatus){
            if(isset($myRequestsCount[$requestStatus]))
                $requestsCount[$requestStatus] = $myRequestsCount[$requestStatus][0]->count;
            else
                $requestsCount[$requestStatus] = 0;
        }
        return !empty($requestsCount)?$requestsCount:null;


    }

}

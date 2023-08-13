<?php

namespace Modules\Roster\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Controller;
use App\Scopes\WithoutDeletedItemsScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\ClassStudentManagementFactory;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Http\Controllers\Classes\ManageRosterStudent\EducatorRosterStudentClass;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Http\Requests\RosterStudent\AddRosterStudentForEducatorRequest;
use Modules\Roster\Http\Requests\RosterStudent\AddRosterStudentRequest;
use Modules\Roster\Http\Requests\RosterStudent\DeleteRosterStudentRequest;
use Modules\Roster\Http\Requests\RosterStudent\DestroyRosterStudentRequest;
use Modules\Roster\Http\Requests\RosterStudent\EnrollToRosterByLinkRequest;
use Modules\Roster\Http\Requests\RosterStudent\GetRosterStudentAttendanceByRosterIdRequest;
use Modules\Roster\Http\Requests\RosterStudent\GetRosterStudentsByRosterIdRequest;
use Modules\Roster\Http\Resources\RosterResource;
use Modules\Roster\Http\Resources\RosterStudentResource;
use Modules\Roster\Models\Roster;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentAttendanceServices;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentPageServices;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\User;

class RosterStudentController extends Controller
{


    public function getRosterStudentsByRosterId(GetRosterStudentsByRosterIdRequest $request,$roster_id){


        if(isset($request->order_by)){

            $orderType = $request->order_by;

            $rosterStudents = RosterStudent::withoutGlobalScopes()
                ->select(['roster_students.*',DB::raw('concat(users.fname," ",users.lname) as full_name')])
                ->where('roster_students.deleted',false)
                ->where('roster_id',$roster_id)
                ->join('class_students','class_students.id','=','roster_students.class_student_id')
                ->where('class_students.deleted',false)
                ->where('class_students.is_active',true)
                ->join('students','students.id','=','class_students.student_id')
                ->join('users','users.id','=','students.user_id')
//                ->orderBy('full_name', $orderType)
                ->order('full_name', $orderType,[
                    User::class=>['full_name']
                ])
                ->with(['ClassStudent.Student.User'])
                ->get();
        }else{
            $rosterStudents = RosterStudent::where('roster_id',$roster_id)
                ->with(['ClassStudent.Student.User'])
                ->get();
        }


        return ApiResponseClass::successResponse(RosterStudentResource::collection($rosterStudents));
    }


    public function addStudentsToRoster(AddRosterStudentRequest $request,$roster_id){
        $roster = $request->getRoster();
        $user = $request->user();
        DB::beginTransaction();
        //get the class that contained the roster
        $classInfo = ClassInfo::with('ClassModel')
            ->find($roster->class_info_id);

        $classStudentManagment = ClassStudentManagementFactory::create($user);
        $classStudents = $classStudentManagment->myClassStudentsByClassIdAndStudentIds($classInfo->ClassModel->id,$request->student_ids);
//        $classStudents = ClassServices::checkIfStudentsBelongsToClass($request->student_ids,$classInfo->ClassModel->id);

        $prepareRosterStudentArrayForCreate = RosterServices::prepareRosterStudentArrayForCreate($classStudents,$roster_id);

        RosterStudent::insert($prepareRosterStudentArrayForCreate);

        RosterAssignmentStudentPageServices::addDefinedStudentPages($roster->id,$request->student_ids);
        RosterAssignmentStudentAttendanceServices::addStudentsAtendancesByRoster($roster->id,$request->student_ids);
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }

    public function addStudentsToRosterForEducator(AddRosterStudentForEducatorRequest $request,$roster_id){
        DB::beginTransaction();
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
        $roster = $request->getRoster();
        $roster->load('ClassInfo.ClassModel');
        $classStudentManagment = ClassStudentManagementFactory::create($user);
        $classStudents = $classStudentManagment->myClassStudentsByClassIdAndStudentIds(
            $roster->ClassInfo->ClassModel->id,$request->student_ids
        );

        foreach ($request->student_ids as $studentId){

            //check if student belongs to my class or create
            $classStudent = $classStudents->where('student_id',$studentId)->first();
            if(is_null($classStudent)){
                $classStudent = ClassServices::addStudentToClass(
                    $accountType,$accountObject,$studentId,$roster->ClassInfo->ClassModel->id
                );
            }

            //check if student belongs to my roster or create
            $rosterStudent = RosterStudent::where('roster_id' , $roster->id)
                ->where('class_student_id' , $classStudent->id)->first();
            if(is_null($rosterStudent)){
                $rosterStudent = RosterServices::addStudentToRoster($roster->id,$classStudent->id);
                RosterAssignmentStudentPageServices::addDefinedStudentPages($roster->id,$studentId);
                RosterAssignmentStudentAttendanceServices::addStudentsAtendancesByRoster($roster->id,$studentId);
            }

        }

        DB::commit();
        return ApiResponseClass::successMsgResponse();


    }

//    /**
//     * when the student click on roster code this api will called
//     * (just the educator who can receive enrolls even the student not belongs to him)
//     */
//    public function enrollToRosterByLink(EnrollToRosterByLinkRequest $request,$code){
//        $user = $request->user();
//        $student = $user->Student;
//        DB::beginTransaction();
//        $roster = Roster::where('code',$code)
//            ->with(['ClassInfo'=>function($query){
//                return $query->with(['Teacher','School','Educator','LevelSubject']);
//            }])
//            /*->deletedAsSoft(false)*/
//            ->firstOrFail();
//        if($roster->is_closed)
//            throw new ErrorMsgException(transMsg('you_cant_enroll_to_closed_roster',ApplicationModules::ROSTER_MODULE_NAME));
//
//        $classInfo = $roster->ClassInfo;
//        //here we throw error because this operation not allowed for another account types
//        if(is_null($classInfo->educator_id))
//            throw new ErrorMsgException();
//
//        $educator = $classInfo->Educator;
//        $educatorRosterStudentClass = new EducatorRosterStudentClass($educator);
//        $myStudent = $educatorRosterStudentClass->getOrCreateStudentFromMyStudent($student);
//        $classStudent = $educatorRosterStudentClass->getOrCreateStudentFromMyClassStudent($student,$classInfo);
//        $foundInRosterStatus = $educatorRosterStudentClass->getOrCreateStudentFromMyRosterStudent($classStudent,$roster);
//
//        RosterAssignmentStudentPageServices::addDefinedStudentPages($roster->id,$student->id);
////        //initialize all assignment pages in the roster to the new student
////        $rosterAssignmentPages = RosterAssignmentPage::whereHas('RosterAssignment',function ($query)use ($roster){
////                return $query->where('roster_id',$roster->id);
////            })
////            ->get();
////        $arrayForCreate = [];
////        foreach ($rosterAssignmentPages as $rosterAssignmentPage) {
////            $arrayForCreate[] = [
////                'roster_assignment_page_id' => $rosterAssignmentPage->id,
////                'student_id' => $student->id,
////                'is_hidden' => $rosterAssignmentPage->is_hidden,
////                'is_locked' => $rosterAssignmentPage->is_locked,
////                'created_at' => Carbon::now(),
////            ];
////        }
////        RosterAssignmentStudentPage::insert($arrayForCreate);
//
//        DB::commit();
//        return ApiResponseClass::successResponse([
//            'message' => $foundInRosterStatus?null:transMsg('enrolled_successfully',ApplicationModules::ROSTER_MODULE_NAME),
//            'roster' => new RosterResource($roster),
//
//        ]);
//
//    }

    public function destroy(DestroyRosterStudentRequest $request,$id){
        $rosterStudent = $request->getRosterStudent();
        $rosterStudent->delete();
        return ApiResponseClass::deletedResponse();
    }

    /*public function deleteStudentsFromRoster(DeleteRosterStudentRequest $request){

    }*/

}

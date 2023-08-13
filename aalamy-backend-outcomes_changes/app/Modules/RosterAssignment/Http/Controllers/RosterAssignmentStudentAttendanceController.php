<?php

namespace Modules\RosterAssignment\Http\Controllers;


use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\AssignmentManagementFactory;
use Modules\Roster\Http\Resources\RosterStudentResource;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Http\Controllers\Classes\Attendance\RosterAssignmentAttendanceClass;
use Modules\RosterAssignment\Http\Controllers\Classes\Attendance\RosterAttendanceClass;
use Modules\RosterAssignment\Http\Controllers\Classes\Attendance\StudentAttendanceClass;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAttendance\GetRosterAssignmentAttendanceRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAttendance\GetRosterAttendanceRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAttendance\GetStudentAttendanceRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAttendance\MarkMeAsPresentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAttendance\MarkStudentAsPresentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignmentStudentAttendance\UpdateRosterAssignmentStudentAttendanceRequest;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentResource;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentStudentAttendanceResource;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAttendance;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;

class RosterAssignmentStudentAttendanceController extends Controller
{


    public function getRosterAssignmentAttendance(GetRosterAssignmentAttendanceRequest $request,$roster_assignment_id){

        $rosterAssignmentAttendanceClass = new RosterAssignmentAttendanceClass(
            $roster_assignment_id
        );
        $rosterAssignmentStudentsAttendances = $rosterAssignmentAttendanceClass->getAttendancePaginate();

        return ApiResponseClass::successResponse(RosterAssignmentStudentAttendanceResource::collection($rosterAssignmentStudentsAttendances));
    }

    public function downloadRosterAssignmentAttendance(GetRosterAssignmentAttendanceRequest $request,$roster_assignment_id){

        $rosterAssignmentAttendanceClass = new RosterAssignmentAttendanceClass(
            $roster_assignment_id
        );
        $path = $rosterAssignmentAttendanceClass->exportAsExcel();

        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);
    }


//    /**
//     * get attendance for defined roster
//     * and you can filter the results on startDate and endDate
//     * and you can filter the results on defined student from this roster
//     */
//    public function getRosterAttendance(GetRosterAttendanceRequest $request,$roster_id){
//        $user = $request->user();
//        $filterRosterAssignmentData = FilterRosterAssignmentAttendanceData::fromRequest($request);
//
////        $rosterAttendanceClass = new RosterAttendanceClass(
////            $roster_id,$user,$filterRosterAssignmentData
////        );
////        $rosterAssignmentStudentsAttendances = $rosterAttendanceClass
////            ->getAttendance();
////            ->groupBy(['student_id','roster_assignment_id']);
//
//        $rosterStudents = RosterStudent::where('roster_id',$roster_id)
//            ->with(['ClassStudent.Student.User'])
//            ->get();
//
//        $rosterAssignments = RosterAssignment::where('roster_id',$roster_id)
//            ->with('Assignment')
//            ->with('RosterAssignmentStudentAttendances.Student')
//            ->get();
//
//        $students = Student::whereHas('RosterAssignmentStudentAttendances',function($query)use($roster_id){
//            return $query->whereHas('RosterAssignment',function($query)use($roster_id){
//                return $query->where('roster_id',$roster_id);
//            });
//        })
//            ->with(['RosterAssignmentStudentAttendances'=>function($query)use($roster_id){
//                return $query->whereHas('RosterAssignment',function($query)use($roster_id){
//                    return $query->where('roster_id',$roster_id);
//                });
//            }])
//        ->get();
//        return $students;
//
////        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
////        $rosterAssignments = $rosterAssignmentClass->myRosterAssignmentsByRosterId($roster_id);
//
//
//        return ApiResponseClass::successResponse([
//            'roster_students' => RosterStudentResource::collection($rosterStudents),
//            'roster_assignments' => RosterAssignmentResource::collection($rosterAssignments),
////            'attendance'=>RosterAssignmentStudentAttendanceResource::collection($rosterAssignmentStudentsAttendances)
////            'attendance'=>$rosterAssignmentStudentsAttendances
//        ]);
//
//
//    }

    /**
     * get attendance for defined roster
     * and you can filter the results on startDate and endDate
     * and you can filter the results on defined student from this roster
     */
    public function getRosterAttendance(GetRosterAttendanceRequest $request,$roster_id){
    //DB::enableQueryLog();


        $user = $request->user();
        $roster = $request->getRoster();
        $filterRosterAssignmentData = FilterRosterAssignmentAttendanceData::fromRequest($request);

        $studentAttendeeCount = null;
        $rosterAssignmentsCount = null;
        if(!isset($request->page) || $request->page==1){
            $rosterAssignments = RosterAssignment::where('roster_id',$roster_id)->get();
            $rosterAssignmentsIds = $rosterAssignments->pluck('id')->toArray();
            $rosterAssignmentsCount = $rosterAssignments->count();


            $studentAttendeeCount = RosterAssignmentStudentAttendance::where('student_id',$filterRosterAssignmentData->student_id)
                ->whereIn('roster_assignment_id',$rosterAssignmentsIds)
                ->isAttendee()
                ->count();
        }


        $rosterAttendanceClass = new RosterAttendanceClass(
            $roster,$user,$filterRosterAssignmentData
        );
        $rosterAssignmentStudentsAttendances = $rosterAttendanceClass->getAttendancePaginate();

//        return DB::getQueryLog();
        return ApiResponseClass::successResponse([
            'student_attendance' => RosterAssignmentStudentAttendanceResource::collection($rosterAssignmentStudentsAttendances),
            'attendee_count' =>    $studentAttendeeCount,
            'assignments_count' => $rosterAssignmentsCount,
        ]);

    }

    public function downloadRosterAttendance(GetRosterAttendanceRequest $request,$roster_id){
        $user = $request->user();
        $roster = $request->getRoster();
        $filterRosterAssignmentAttendanceData = FilterRosterAssignmentAttendanceData::fromRequest($request);

        $rosterAttendanceClass = new RosterAttendanceClass(
            $roster,$user,$filterRosterAssignmentAttendanceData
        );
        if(isset($filterRosterAssignmentAttendanceData->student_id))
            $path = $rosterAttendanceClass->exportAsExcelForDefinedStudent();
        else
            $path = $rosterAttendanceClass->exportAsExcel();


        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);

    }


    public function getStudentAttendance(GetStudentAttendanceRequest $request,$student_id){
        $user = $request->user();
        $filterRosterAssignmentAttendanceData = FilterRosterAssignmentAttendanceData::fromRequest($request);

        $studentAttendanceClass = new StudentAttendanceClass(
            $student_id,$user,$filterRosterAssignmentAttendanceData
        );
        $rosterAssignmentStudentsAttendance = $studentAttendanceClass->getAttendancePaginate();

        return ApiResponseClass::successResponse(
            RosterAssignmentStudentAttendanceResource::collection($rosterAssignmentStudentsAttendance)
        );

    }

    public function downloadStudentAttendance(GetStudentAttendanceRequest $request,$student_id){
        $user = $request->user();
        $filterRosterAssignmentAttendanceData = FilterRosterAssignmentAttendanceData::fromRequest($request);

        $studentAttendanceClass = new StudentAttendanceClass(
            $student_id,$user,$filterRosterAssignmentAttendanceData
        );
        $path = $studentAttendanceClass->exportAsExcel();

        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);

    }


    public function markMeAsPresent(MarkMeAsPresentRequest $request,$roster_assignment_id){
        $user = $request->user();
        list(,$student) = UserServices::getAccountTypeAndObject($user);
        $rosterAssignmentStudentAttendance = RosterAssignmentStudentAttendance::where('student_id',$student->id)
            ->where('roster_assignment_id',$roster_assignment_id)
            ->firstOrFail();
        $rosterAssignmentStudentAttendance->markAsPresent();
        return ApiResponseClass::successMsgResponse();
    }


    public function markStudentAsPresent(MarkStudentAsPresentRequest $request,$roster_assignment_id,$student_id){
        $rosterAssignmentStudentAttendance = RosterAssignmentStudentAttendance::where('student_id',$student_id)
            ->where('roster_assignment_id',$roster_assignment_id)
            ->firstOrFail();
        $rosterAssignmentStudentAttendance->markAsPresent();
        return ApiResponseClass::successMsgResponse();
    }

    /**
     * store note with attendee_status
     */
    public function update(UpdateRosterAssignmentStudentAttendanceRequest $request,$roster_assignment_id,$student_id){

        $rosterAssignmentStudentAttendance = RosterAssignmentStudentAttendance::where('student_id',$student_id)
            ->where('roster_assignment_id',$roster_assignment_id)
            ->firstOrFail();
        $rosterAssignmentStudentAttendance->update([
            'attendee_status' => $request->attendee_status,
            'note' => $request->note,
        ]);


        /*$rosterAssignmentStudentAttendance = $request->getRosterAssignmentStudentAttendance();
        $rosterAssignmentStudentAttendance->update([
            'attendee_status' => $request->attendee_status,
            'note' => $request->note,
        ]);*/
        return ApiResponseClass::successResponse(new RosterAssignmentStudentAttendanceResource($rosterAssignmentStudentAttendance));
    }





}

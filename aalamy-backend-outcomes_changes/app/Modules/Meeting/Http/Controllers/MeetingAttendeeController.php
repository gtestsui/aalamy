<?php

namespace Modules\Meeting\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Controller;
use App\Modules\Meeting\Http\Requests\MeetingAttendee\GetMyOwnMeetingAttendanceRequest;
use App\Modules\Meeting\Http\Requests\MeetingAttendee\GetStudentAttendeeRequest;
use App\Modules\Meeting\Http\Requests\MeetingAttendee\UpdateAttendeeRequest;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Attendance\MeetingAttendanceClass;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Attendance\StudentMeetingsAttendanceClass;
use Modules\Meeting\Http\Resources\MeetingResource;
use Modules\User\Models\Student;

class MeetingAttendeeController extends Controller
{


    public function updateAttendeeState(UpdateAttendeeRequest $request,$meeting_targeted_id){
        $meetingTarget = $request->getMeetingTarget();
        $meetingTarget->update([
            'attendee_status' => $request->attendee_status,
            'note' => $request->note,
        ]);
//        $meetingTarget->oppositeAttendeeStatus();
        return ApiResponseClass::successMsgResponse();
    }


    public function getMeetingAttendance(GetMyOwnMeetingAttendanceRequest $request,$meeting_id){
        $user = $request->user();
        $meeting = $request->getMeeting();
        $meeting->load(['TargetUsers'=>function($query){
            return $query->whereNotNull('student_id')->with('Student.User');
        }]);
        return ApiResponseClass::successResponse(new MeetingResource($meeting));
    }

    public function downloadMeetingAttendance(GetMyOwnMeetingAttendanceRequest $request,$meeting_id){
        $user = $request->user();
        $meeting = $request->getMeeting();
        $meeting->load(['TargetUsers'=>function($query){
            return $query->whereNotNull('student_id')->with('Student.User');
        }]);
        $meetingAttendanceClass = new MeetingAttendanceClass($meeting);
        $path = $meetingAttendanceClass->exportAsExcel();
        /*$meetingName  = str_replace(' ','-',$meeting->title);
        $time = Carbon::now()->microsecond;

        $innerPath = "student-attendances/"
            .'meeting/'
            ."$meetingName/"
            .'all-students/'
            ."$time/"
            ."$meetingName-$time.xlsx";

        Excel::store(new MeetingAttendanceExport($meeting),
            $innerPath
        );

        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";
*/

        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);

    }

    public function getStudentAttendance(GetStudentAttendeeRequest $request,$student_id){
        $user = $request->user();
        $studentMeetingsAttendanceClass = new StudentMeetingsAttendanceClass(
            $student_id,
            $user,
            $request->start_date,
            $request->edn_date,
            $request->meetings_ids
        );
        $meetings = $studentMeetingsAttendanceClass->getAttendancePaginate();

//        $meetingManagementClass = MeetingOwnerManagementFactory::create($user);
//        $meetings = $meetingManagementClass->myMeetingsQuery()
//            ->whereHas('TargetUsers',function ($query)use ($student_id){
//                return $query->where('student_id',$student_id);
//            })
//            ->with(['TargetUsers' => function ($query)use ($student_id){
//                return $query->where('student_id',$student_id)->with('Student.User');
//            }])
//            ->when(isset($request->start_date),function ($query)use ($request){
//                return $query->where('date_time','>=',$request->start_date);
//            })
//            ->when(isset($request->end_date),function ($query)use ($request){
//                return $query->where('date_time','<=',$request->end_date);
//            })
//            ->when(isset($request->meetings_ids),function ($query)use ($request){
//                return $query->whereIn('id',$request->meetings_ids);
//            })
//            ->get();

        return ApiResponseClass::successResponse(MeetingResource::collection($meetings));

    }


    public function downloadStudentMeetingsAttendance(GetStudentAttendeeRequest $request,$student_id){
        $user = $request->user();
        $student = Student::with('User')->findOrFail($student_id);

        $studentMeetingsAttendanceClass = new StudentMeetingsAttendanceClass(
            $student_id,
            $user,
            $request->start_date,
            $request->edn_date,
            $request->meetings_ids
        );
        $path = $studentMeetingsAttendanceClass->exportAsExcel();

        /*$meetingManagementClass = MeetingOwnerManagementFactory::create($user);
        $meetings = $meetingManagementClass->myMeetingsQuery()
                ->whereHas('TargetUsers',function ($query)use ($student_id){
                return $query->where('student_id',$student_id);
            })
            ->with(['TargetUsers' => function ($query)use ($student_id){
                return $query->where('student_id',$student_id);
            }])
            ->when(isset($request->start_date),function ($query)use ($request){
                return $query->where('date_time','>=',$request->start_date);
            })
            ->when(isset($request->end_date),function ($query)use ($request){
                return $query->where('date_time','<=',$request->end_date);
            })
            ->when(isset($request->meetings_ids),function ($query)use ($request){
                return $query->whereIn('id',$request->meetings_ids);
            })
            ->get();*/

        /*$studentName  = str_replace(' ','-',$student->User->getFullName());
        $time = (Carbon::now())->setTimezone($request->time_zone)->microsecond;
        $fromDate = isset($request->start_date)?$request->start_date:null;
        $toDate = isset($request->end_date)?$request->end_date:$time;

        $innerPath = "student-attendances/"
            .'students/'
            ."$studentName/"
            .'meetings/'
            ."$time/"
            ."$studentName-$fromDate-$toDate.xlsx";

        Excel::store(new StudentMeetingsAttendanceExport($meetings),
            $innerPath
        );

        $path = FileSystemServicesClass::getDefaultStoragePathInsideDisk()."/$innerPath";*/

        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);


    }





}

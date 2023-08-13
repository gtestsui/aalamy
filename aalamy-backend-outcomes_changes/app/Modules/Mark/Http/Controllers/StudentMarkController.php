<?php

namespace Modules\Mark\Http\Controllers;


use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Controller;
use Modules\Mark\Http\Controllers\Classes\Mark\RosterAssignmentMarkClass;
use Modules\Mark\Http\Controllers\Classes\Mark\RosterMarkClass;
use Modules\Mark\Http\Controllers\Classes\Mark\StudentMarkClass;
use Modules\Mark\Http\Requests\Mark\GetRosterAssignmentMarkRequest;
use Modules\Mark\Http\Requests\Mark\GetRosterMarkRequest;
use Modules\Mark\Http\Requests\Mark\GetStudentMarkRequest;
use Modules\Mark\Http\Resources\RosterAssignmentMarkResource;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\Mark\Http\Resources\StudentMarkResource;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\User\Models\Student;

class StudentMarkController extends Controller
{


//    public function getRosterMarks(GetRosterMarkRequest $request){
//        $user = $request->user();
//        $filterRosterAssignmentData = FilterRosterAssignmentData::fromRequest($request);
//
//
//        $rosterMarkClass = new RosterMarkClass($user,$filterRosterAssignmentData);
//        $students = $rosterMarkClass->getMarks();
//
//        return  ApiResponseClass::successResponse([
//            'students' => $students,
//        ]);
//
//    }


    public function getRosterAssignmentMarks(GetRosterAssignmentMarkRequest $request, $roster_assignment_id)
    {
        $rosterAssignment = $request->getRosterAssignment();
        $rosterAssignment->load('Assignment');
        $markClass = new RosterAssignmentMarkClass($rosterAssignment);
        $students = $markClass->getMarks();

        return ApiResponseClass::successResponse([
            'roster_assignment' => [
                'start_date' => $rosterAssignment->start_date,
                'assignment_name' => $rosterAssignment->Assignment->name
            ],
            'roster_assignment_students_marks' => RosterAssignmentMarkResource::collection($students),
        ]);
    }

    public function downloadRosterAssignmentMarks(GetRosterAssignmentMarkRequest $request, $roster_assignment_id)
    {
        $rosterAssignment = $request->getRosterAssignment();
        $rosterAssignment->load('Assignment');
        $markClass = new RosterAssignmentMarkClass($rosterAssignment);
        $path = $markClass->exportAsExcel();

        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);
    }


    public function downloadRosterMarks(GetRosterMarkRequest $request, $roster_id)
    {
        $user = $request->user();
        $roster = $request->getRoster();
        $filterRosterAssignmentData = FilterRosterAssignmentData::fromRequest($request);


        $rosterMarkClass = new RosterMarkClass(
            $user, $roster, $filterRosterAssignmentData
        );
//        $students = $rosterMarkClass->getMarks();
        $path = $rosterMarkClass->exportAsExcel();

        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);

    }

    public function getStudentMark(GetStudentMarkRequest $request, $student_id)
    {
        $user = $request->user();
        $student = Student::with('User')->findOrFail($student_id);
        $filterRosterAssignmentAttendanceData = FilterRosterAssignmentAttendanceData::fromRequest($request);


        $markClass = new StudentMarkClass($student, $user, $filterRosterAssignmentAttendanceData);
        $rosterAssignments = $markClass->getMarks();


        return ApiResponseClass::successResponse([
            'student' => [
                'id' => $student->id,
                'fname' => $student->User->fname,
                'lname' => $student->User->lname,
                'image' => $student->User->image,
            ],
            'mark' => StudentMarkResource::collection($rosterAssignments),
        ]);
    }

    public function downloadStudentMark(GetStudentMarkRequest $request, $student_id)
    {
        $user = $request->user();
        $student = Student::with('User')->findOrFail($student_id);
        $filterRosterAssignmentAttendanceData = FilterRosterAssignmentAttendanceData::fromRequest($request);


        $markClass = new StudentMarkClass($student, $user, $filterRosterAssignmentAttendanceData);
        $path = $markClass->exportAsExcel();


        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);
    }


}

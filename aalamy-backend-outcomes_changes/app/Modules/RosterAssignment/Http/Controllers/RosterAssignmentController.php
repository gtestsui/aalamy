<?php

namespace Modules\RosterAssignment\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\ClassStudentManagementFactory;
use Modules\Notification\Jobs\RosterAssignment\SendNewRosterAssignmentNotification;
use Modules\Roster\Models\RosterStudent;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\StudentRosterAssignment;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentPageServices;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentAttendanceServices;
use Modules\RosterAssignment\Http\Controllers\Classes\RosterAssignmentStudentPageServices;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\RosterAssignment\Http\DTO\RosterAssignmentData;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\AddAssignmentToManyRostersRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\DestroyRosterAssignmentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\GetByAssignmentIdRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\GetByLevelSubjectIdForParentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\GetByLevelSubjectIdRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\GetByRosterIdRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\GetMyRosterAssignmentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\GetRosterAssignmentRosterByIdForGenerateGradeBookRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\GetStudentRosterAssignmentBetweenTowDatesRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\LinkRosterToManyAssignmentsRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\MergeRosterAssignmentPdfsAndDownloadRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\ShowRosterAssignmentForParentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\ShowRosterAssignmentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\StoreRosterAssignmentRequest;
use Modules\RosterAssignment\Http\Requests\RosterAssignment\UpdateRosterAssignmentRequest;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentResource;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\Student;
use Modules\User\Models\User;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class RosterAssignmentController extends Controller
{


    public function getByLevelSubjectIdPaginate(GetByLevelSubjectIdRequest $request)
    {
        $user = $request->user();
        $user->load('Student');

        $studentRosterAssignment = new StudentRosterAssignment($user->Student);
        $rosterAssignments = $studentRosterAssignment->myRosterAssignmentsByLevelSubjectPaginate($request->level_subject_id);

        return ApiResponseClass::successResponse(RosterAssignmentResource::collection($rosterAssignments));

    }

    public function getByLevelSubjectIdByStudentForParentPaginate(GetByLevelSubjectIdForParentRequest $request, $student_id)
    {
        $user = $request->user();
        $student = Student::find($student_id);
        $studentUser = User::find($student->user_id);
        $studentRosterAssignment = new StudentRosterAssignment($student);
        $rosterAssignments = $studentRosterAssignment->myRosterAssignmentsByLevelSubjectPaginate($request->level_subject_id);

//        return ApiResponseClass::successResponse(RosterAssignmentResource::collection($rosterAssignments));

        return ApiResponseClass::successResponse([
            'roster_assignments' => RosterAssignmentResource::collection($rosterAssignments),
            'student_user' => new UserResource($studentUser),
        ]);


    }


    //just the educator or school who can use this api (the real owner of the assignment)
    public function getByAssignmentId(GetByAssignmentIdRequest $request, $assignment_id)
    {
        $user = $request->user();
        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $rosterAssignments = $rosterAssignmentClass->myRosterAssignmentsByAssignmentId($assignment_id);
        return ApiResponseClass::successResponse(RosterAssignmentResource::collection($rosterAssignments));
    }

    public function getByRosterId(GetByRosterIdRequest $request, $roster_id)
    {
        $user = $request->user();
        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $rosterAssignments = $rosterAssignmentClass->myRosterAssignmentsByRosterId($roster_id);
        return ApiResponseClass::successResponse(RosterAssignmentResource::collection($rosterAssignments));
    }

    public function getByRosterIdForGenerateGradeBook(GetRosterAssignmentRosterByIdForGenerateGradeBookRequest $request, $roster_id)
    {
        $user = $request->user();
        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $rosterAssignments = $rosterAssignmentClass
            ->setFilter(FilterRosterAssignmentData::fromRequest($request))
            ->myEndedRosterAssignmentsByRosterId($roster_id);
        return ApiResponseClass::successResponse(RosterAssignmentResource::collection($rosterAssignments));
    }


    public function getMyRosterAssignmentPaginate(GetMyRosterAssignmentRequest $request)
    {
        $user = $request->user();
        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $rosterAssignments = $rosterAssignmentClass->myRosterAssignmentsByMyRostersPaginate();
        return ApiResponseClass::successResponse(RosterAssignmentResource::collection($rosterAssignments));

    }

    public function getStudentRosterAssignmentsBetweenTowDatesPaginate(GetStudentRosterAssignmentBetweenTowDatesRequest $request, $student_id)
    {
        $user = $request->user();

        $manageClassStudent = ClassStudentManagementFactory::create($user, $request->my_teacher_id);
        $myClassStudents = $manageClassStudent->myClassStudentByStudentId($student_id);

        //get all roster ids belongs to my classes that contain the student inside
        $classStudentIds = $myClassStudents->pluck('id');
        $rosterIds = RosterStudent::whereIn('class_student_id', $classStudentIds)
            ->pluck('roster_id')->toArray();

        $filterRosterAssignmentData = FilterRosterAssignmentData::fromArray([
            'roster_ids' => count($rosterIds) ? $rosterIds : [-1],
            'start_date' => $request->from_date,
            'end_date' => $request->to_date
        ]);


        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $rosterAssignments = $rosterAssignmentClass
            ->setFilter($filterRosterAssignmentData)
            ->myRosterAssignmentsByMyRostersPaginate();


        return ApiResponseClass::successResponse(RosterAssignmentResource::collection($rosterAssignments));

    }

    public function store(StoreRosterAssignmentRequest $request)
    {
        $user = $request->user();
        DB::beginTransaction();
        $assignment = $request->getAssignment();
        $rosterAssignmentData = RosterAssignmentData::fromRequest($request/*,$assignment*/);
        $rosterAssignment = RosterAssignment::create($rosterAssignmentData->all());
//        AssignmentStudentPageServices::addStudentPages($rosterAssignment->assignment_id,$rosterAssignment->roster_id);
        RosterAssignmentPageServices::addRosterAssignmentPages($rosterAssignment->assignment_id, $rosterAssignment->roster_id);
        RosterAssignmentStudentPageServices::addStudentPages($rosterAssignment->assignment_id, $rosterAssignment->roster_id);
        RosterAssignmentStudentAttendanceServices::initializeRosterAssignmentAttendance($rosterAssignment->assignment_id, $rosterAssignment->roster_id);
        DB::commit();
        ServicesClass::dispatchJob(new SendNewRosterAssignmentNotification($rosterAssignmentData->assignment_id, $rosterAssignmentData->roster_id, $user));
        return ApiResponseClass::successResponse(new RosterAssignmentResource($rosterAssignment));
    }

    // this api need to update and make some authorization on student
    public function show(ShowRosterAssignmentRequest $request, $roster_assignment_id)
    {
        $user = $request->user();
        $rosterAssignment = $request->getRosterAssignment();

        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $rosterAssignment = $rosterAssignmentClass->loadDetails($rosterAssignment);

//        $rosterAssignment->load(['Assignment'=>function($query){
//            return $query->with(['LevelSubject'=>function($query){
//                return $query->with(['Level','Subject']);
//            },'Unit','Lesson','Pages']);
//        }]);
        return ApiResponseClass::successResponse(new RosterAssignmentResource($rosterAssignment));

    }

    // this api need to update and make some authorization on student
    public function showForParent(ShowRosterAssignmentForParentRequest $request, $roster_assignment_id, $student_id)
    {
        $user = $request->user();

        $student = Student::findOrFail($student_id);

        $rosterAssignmentClass = new StudentRosterAssignment($student);
        $rosterAssignment = $rosterAssignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentIdOrFail($roster_assignment_id);
        $rosterAssignment = $rosterAssignmentClass->loadDetails($rosterAssignment);

        return ApiResponseClass::successResponse(new RosterAssignmentResource($rosterAssignment));

    }

    public function addAssignmentToManyRosters(AddAssignmentToManyRostersRequest $request)
    {
        $user = $request->user();
        $assignment = $request->getAssignment();
        $rosterAssignmentData = RosterAssignmentData::fromRequest($request/*,$assignment*/);
        DB::beginTransaction();
        DB::beginTransaction();
        $arrayForCreate = AssignmentServices::prepareAssignmentRostersArrayForCreate($rosterAssignmentData, $user, $request->my_teacher_id);

        RosterAssignment::insert($arrayForCreate);
        $rosterIds = array_column($arrayForCreate, 'roster_id');
//        AssignmentStudentPageServices::addStudentPages($assignment->id,$rosterIds);

        DB::commit();
        RosterAssignmentPageServices::addRosterAssignmentPages($assignment->id, $rosterIds);
        RosterAssignmentStudentPageServices::addStudentPages($assignment->id, $rosterIds);
        RosterAssignmentStudentAttendanceServices::initializeRosterAssignmentAttendance($assignment->id, $rosterIds);
        DB::commit();
        ServicesClass::dispatchJob(new SendNewRosterAssignmentNotification($rosterAssignmentData->assignment_id, $rosterIds, $user));

        return ApiResponseClass::successMsgResponse();
    }

    public function linkRosterToManyAssignments(LinkRosterToManyAssignmentsRequest $request)
    {
        $user = $request->user();
        $roster = $request->getRoster();
        $rosterAssignmentData = RosterAssignmentData::fromRequest($request);
        DB::beginTransaction();
        DB::beginTransaction();
        $arrayForCreate = AssignmentServices::prepareRosterAssignmentsArrayForCreate($rosterAssignmentData, $user, $request->my_teacher_id);

        RosterAssignment::insert($arrayForCreate);
        $assignmentIds = array_column($arrayForCreate, 'assignment_id');
//        AssignmentStudentPageServices::addStudentPages($assignmentIds,$roster->id);
        DB::commit();
        RosterAssignmentPageServices::addRosterAssignmentPages($assignmentIds, $roster->id);
        RosterAssignmentStudentPageServices::addStudentPages($assignmentIds, $roster->id);
        RosterAssignmentStudentAttendanceServices::initializeRosterAssignmentAttendance($assignmentIds, $roster->id);
        DB::commit();
        ServicesClass::dispatchJob(new SendNewRosterAssignmentNotification($assignmentIds, $rosterAssignmentData->roster_id, $user));

        return ApiResponseClass::successMsgResponse();
    }


    public function mergeRosterAssignmentPdfsAndDownload(MergeRosterAssignmentPdfsAndDownloadRequest $request,$student_user_id,$roster_assignment_id){


        $pdf = PDFMergerFacade::init();

        foreach ($request->pdf_files as $key => $value) {
            $path = FileManagmentServicesClass::storeBase64File($value,'roster-assignment-pages-merger-for-download');
            $pdf->addPDF(FileSystemServicesClass::getDiskBaseRoot().$path, 'all');
        }

        $fileName = 'roster-assignment'.$roster_assignment_id.'-student-user'.$student_user_id.'.pdf';
        $pdf->merge();
        $pdf->save(FileSystemServicesClass::getDiskRoot().
            "/roster-assignment-for-download/".$fileName
        );

        $b64Doc = chunk_split(base64_encode(file_get_contents(FileSystemServicesClass::getDiskRoot().
            "/roster-assignment-for-download/".$fileName)));


        return ApiResponseClass::successResponse([
//            'file' => $b64Doc,
            'path' => baseRoute()."storage/roster-assignment-for-download/".$fileName,

        ]);

    }


    public function update(UpdateRosterAssignmentRequest $request, $id)
    {
        $rosterAssignment = $request->getRosterAssignment();
        $rosterAssignmentData = RosterAssignmentData::fromRequest($request, $rosterAssignment);

        $rosterAssignment->update($rosterAssignmentData->initializeForUpdate($rosterAssignmentData));
        return ApiResponseClass::successResponse(new RosterAssignmentResource($rosterAssignment));
    }

    public function destroy(DestroyRosterAssignmentRequest $request, $id)
    {
        $rosterAssignment = $request->getRosterAssignment();
        $rosterAssignment->delete();
        return ApiResponseClass::deletedResponse();
    }


}

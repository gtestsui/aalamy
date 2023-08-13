<?php

namespace Modules\Mark\Http\Controllers;


use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Mark\Http\Controllers\Classes\ManageGradeBook\GradeBookManagementFactory;
use Modules\Mark\Http\Controllers\Classes\GradeBookProcessor\GradeBookClass;
use Modules\Mark\Http\DTO\GradeBookData;
use Modules\Mark\Http\Requests\GradeBook\GenerateGradeBookRequest;
use Modules\Mark\Http\Requests\GradeBook\GetMyGradeBooksRequest;
use Modules\Mark\Http\Requests\GradeBook\ShowGradeBookRequest;
use Modules\Mark\Http\Resources\GradeBookResource;
use Modules\Quiz\Http\Resources\QuizResource;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentResource;

class GradeBookController extends Controller
{


    public function getMyGradeBooksPaginate(GetMyGradeBooksRequest $request){
        $user = $request->user();
        $gradeBookManagmentClass = GradeBookManagementFactory::create($user);
        $gradeBooks = $gradeBookManagmentClass->getMyGradeBooksPaginate();
        return ApiResponseClass::successResponse(GradeBookResource::collection($gradeBooks));
    }

    public function show(ShowGradeBookRequest $request,$grade_book_id){
        $user = $request->user();
        $gradeBook = $request->getGradeBook();
        $gradeBook->load([
            'Teacher',
            'Roster',
            'LevelSubject.Level',
            'LevelSubject.Subject',
            'GradeBookQuizzes.Quiz',
            'GradeBookRosterAssignments.RosterAssignment.Assignment',
            'GradeBookExternalMarks.Student.User',
        ]);
        return ApiResponseClass::successResponse(new GradeBookResource($gradeBook));

    }

    public function generateGradeBook(GenerateGradeBookRequest $request,$roster_id)
    {
        $user = $request->user();
        DB::beginTransaction();

        $gradeBookData = GradeBookData::fromRequest($request);

        $gradeBookManagmentClass = GradeBookManagementFactory::create($user);
        $gradeBook = $gradeBookManagmentClass->createGradeBook($gradeBookData);

        $gradeClass = (new GradeBookClass($roster_id,$gradeBookData,$gradeBook))
            ->prepareRosterAssignments()
            ->prepareQuizzes()
            ->prepareExternalMarks();

        list($students,$path,$targetRosterAssignments,$targetQuizzes,$thereAnExternalMarks)
            = $gradeClass->getMarksAndExports();
        $gradeBook->update([
            'file' => $path
        ]);
        DB::commit();
        return ApiResponseClass::successResponse([
           'students' => $students,
           'exported_path' => FileManagmentServicesClass::getFullPath($path),
           'roster_assignments' => RosterAssignmentResource::collection($targetRosterAssignments),
           'quizzes' => QuizResource::collection($targetQuizzes),
           'with_external_marks' => $thereAnExternalMarks,
        ]);

    }

    public function downloadGradeBook(GenerateGradeBookRequest $request,$roster_id){
        $user = $request->user();

        $gradeBookData = GradeBookData::fromRequest($request);

        $gradeBookManagmentClass = GradeBookManagementFactory::create($user);
        $gradeBook = $gradeBookManagmentClass->createGradeBook($gradeBookData);

        $gradeClass = (new GradeBookClass($roster_id,$gradeBookData,$gradeBook))
            ->prepareRosterAssignments()
            ->prepareQuizzes()
            ->prepareExternalMarks();

        $path = $gradeClass->exportAsExcel();

        return ApiResponseClass::successResponse([
            'exported_path' => FileManagmentServicesClass::getFullPath($path)
        ]);



    }


}

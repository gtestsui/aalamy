<?php

namespace Modules\Level\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageSubject\StudentSubject;
use Modules\Level\Http\Controllers\Classes\ManageSubject\SubjectManagementFactory;
use Modules\Level\Http\Requests\Subject\GetMySubjectsGroupedByOwnerRequest;
use Modules\Level\Http\DTO\SubjectData;
use Modules\Level\Http\Requests\Subject\DestroySubjectRequest;
use Modules\Level\Http\Requests\Subject\GetMySubjectsBySemesterRequest;
use Modules\Level\Http\Requests\Subject\GetMySubjectsRequest;
use Modules\Level\Http\Requests\Subject\GetStudentSubjectsForParentRequest;
use Modules\Level\Http\Requests\Subject\StoreSubjectRequest;
use Modules\Level\Http\Requests\Subject\UpdateSubjectRequest;
use Modules\Level\Http\Resources\SubjectResource;
use Modules\Level\Models\BaseLevelSubject;
use Modules\Level\Models\Level;
use Modules\Level\Models\Subject;
use Modules\User\Models\Student;

class SubjectController extends Controller
{

    public function mySubjects(GetMySubjectsRequest $request){
        $user = $request->user();
        $manageClass = SubjectManagementFactory::create($user,$request->my_teacher_id);
        $mySubjects = $manageClass->mySubjects();
        return ApiResponseClass::successResponse(SubjectResource::collection($mySubjects));

    }

/**
     * the response is array<int,array<int,Subject>>
     * each one of the inner arrays its have just subjects belongs to same owner
     * so each inner array its belongs to one owner
     * @param GetMySubjectsGroupedByOwnerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mySubjectsGroupedByOwner(GetMySubjectsGroupedByOwnerRequest $request){

        $user = $request->user();
        $user->load('Student');
        $studentSubject = new StudentSubject($user->Student);
        $mySubjectsGroupedByOwner = $studentSubject->mySubjectsQuery()
            ->with('User.School')
            ->get()
            ->groupBy('user_id')
            ->values();

        return ApiResponseClass::successResponse($mySubjectsGroupedByOwner);


        return ApiResponseClass::successResponse([
            'educators_with_subjects' => $educatorsWithSubjects,
            'school_with_subjects' => $schoolWithSubjects,
        ]);

    }

    public function mySubjectsBySemester(GetMySubjectsBySemesterRequest $request){
        $user = $request->user();
        $classManagement = ClassManagementFactory::create($user);
        $class = $classManagement->myClassesById($request->class_id);
        $manageClass = SubjectManagementFactory::create($user,$request->my_teacher_id);
        $mySubjects = $manageClass->mySubjectsBySemester($request->semester,$class->level_id);
        return ApiResponseClass::successResponse(SubjectResource::collection($mySubjects));

    }

    public function getSubjectRules($subject_id,$class_id){
        $class = ClassModel::findOrFail($class_id);
        $level = Level::findOrFail($class->level_id);
        $subject = Subject::findOrFail($subject_id);
        $baseLevelSubject = BaseLevelSubject::where('base_subject_id',$subject->base_subject_id)
            ->where('base_level_id',$level->base_level_id)
            ->with('Rule')
            ->first();

        return ApiResponseClass::successResponse($baseLevelSubject->Rule);

    }


    public function getStudentSubjectsForParent(GetStudentSubjectsForParentRequest $request,$student_id){
        $user = $request->user();
        $student = Student::find($student_id);
        $studentSubjectClass = new StudentSubject($student);
        $subjects = $studentSubjectClass->mySubjects();
        return ApiResponseClass::successResponse(SubjectResource::collection($subjects));

    }

    public function mySubjectsExceptBelongsToLevel(GetMySubjectsRequest $request){
        $user = $request->user();
        $manageClass = SubjectManagementFactory::create($user,$request->my_teacher_id);
        $mySubjects = $manageClass->mySubjectsExceptBelongsToLevel($request->level_id);
        return ApiResponseClass::successResponse(SubjectResource::collection($mySubjects));

    }

    public function store(StoreSubjectRequest $request){
        $user = $request->user();
        $subjectData = SubjectData::fromRequest($request,$user);
        $subject = Subject::create($subjectData->all());
        return ApiResponseClass::successResponse(new SubjectResource($subject));

    }

    public function update(UpdateSubjectRequest $request,$id){
        $user = $request->user();
        $subject = $request->getSubject();
        $subjectData = SubjectData::fromRequest($request,$user);
        $subject->update($subjectData->initializeForUpdate($subjectData));
        return ApiResponseClass::successResponse(new SubjectResource($subject));
    }

    public function softDelete(DestroySubjectRequest $request,$id){
        DB::beginTransaction();
        $subject = $request->getSubject();
        $subject->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }


    public function destroy(DestroySubjectRequest $request,$id){
        $subject = $request->getSubject();
        $subject->delete();
        return ApiResponseClass::deletedResponse();
    }



}

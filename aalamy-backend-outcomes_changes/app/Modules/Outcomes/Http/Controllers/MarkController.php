<?php

namespace Modules\Outcomes\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\ClassStudentManagementFactory;
use Modules\Outcomes\Http\Controllers\Classes\OutcomesServices;
use Modules\Outcomes\Http\DTO\MarkData;
use Modules\Outcomes\Http\Requests\Mark\GetStudentsMarksByClassIdRequest;
use Modules\Outcomes\Http\Requests\Mark\UpdateStudentsMarksRequest;
use Modules\Outcomes\Http\Resources\MarkResource;
use Modules\Outcomes\Http\Resources\StudentStudyingInformationResource;
use Modules\Outcomes\Models\StudentStudyingInformation;
use Modules\Setting\Models\YearSetting;
use Modules\User\Http\Controllers\Classes\UserServices;

class MarkController extends Controller
{

    public function getStudentsMarksByClassIdAndSubjectId(GetStudentsMarksByClassIdRequest $request,$class_id,$subject_id){
        //we should change this after detect the permissions for the users whose can use this function
        $user = $request->user();
        $class = $request->getClass();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);

        $schoolId = $accountObject->id;
        if($accountType == 'teacher'){
            $schoolId = $accountObject->school_id;
        }
        //get class students ids
        $classStudentManagament = ClassStudentManagementFactory::create($user);
        $studentIds = $classStudentManagament->myClassStudentsByClassIdQuery($class->id)
        	->active(true)
            ->pluck('student_id')
            ->toArray();

        $yearSetting = YearSetting::firstOrFail();
        $studentsStudyingInformation = StudentStudyingInformation::query()
            ->where('level_id',$class->level_id)//to fetch just the current level , maybe the student have data in level 1,2,3,..
            ->where('school_id',$schoolId)
            ->whereYear('study_year',$yearSetting->start_date)
            ->whereIn('student_id',$studentIds)
            ->with(['Student.User','Marks'=>function($query)use($request){
                return $query->where('subject_id',$request->subject_id);
            }])
            ->get();

        return ApiResponseClass::successResponse(
            StudentStudyingInformationResource::collection($studentsStudyingInformation)
        );
    }


    public function update(UpdateStudentsMarksRequest $request,$mark_id){
        $user = $request->user();
        $mark = $request->getMark();
        $markData = MarkData::fromRequest($request);

        $finalMark = OutcomesServices::generateFinalMarks($mark,$markData);
        $markData->merge([
            'final_mark' => $finalMark
        ]);

        $mark->update($markData->initializeForUpdate());
        return ApiResponseClass::successResponse(new MarkResource($mark));

    }


}

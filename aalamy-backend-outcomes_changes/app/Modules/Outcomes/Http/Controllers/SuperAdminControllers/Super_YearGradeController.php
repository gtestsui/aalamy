<?php

namespace Modules\Outcomes\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\EducatorLevel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\Level\Http\Requests\SuperAdminRequests\Level\GetLevelsByUserRequest;
use Modules\Level\Http\Resources\LevelResource;
use Modules\Level\Models\Level;
use Modules\Outcomes\Http\Controllers\Classes\YearGradeTemplateClass;
use Modules\Outcomes\Models\StudentStudyingInformation;
use Modules\Outcomes\Models\YearGradesGeneralInfo;
use Modules\Outcomes\Models\YearGradesTemplate;
use Modules\Setting\Models\YearSetting;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\User;

class Super_YearGradeController extends Controller
{



    public function getForDefinedStudentByActiveClass(Request $request,$student_id){
        $user = $request->user();
        $classStudent = ClassStudent::active()
            ->where('student_id',$student_id)
            ->with([
                'School',
                'ClassModel.Level',
            ])
            ->firstOrFail();
        $level = Level::with('User.School')->findOrFail($classStudent->ClassModel->Level->id);
        $schoolUser = $level->User;
        $level_id = $classStudent->ClassModel->Level->id;
        $school = $classStudent->School;
        $school = School::with('User')->findOrFail($school->id);
        $levelClass = LevelManagementFactory::create($schoolUser);

        $yearSetting = YearSetting::first();

        $studentStudyingInformation = StudentStudyingInformation::where('student_id',$student_id)
            ->where('level_id',$level_id)
            ->whereYear('study_year',$yearSetting->start_date)
            ->first();
        if(is_null($studentStudyingInformation)){
            $studentStudyingInformation = StudentStudyingInformation::create([
                'student_id' => $student_id,
                'school_id' => $school->id,
                'level_id' => $level_id,
                'study_year' => $yearSetting->start_date,
            ]);
        }


        $level = $levelClass->myLevelsById($level_id);
        $yearTemplate = YearGradesTemplate::where('base_level_id',$level->base_level_id)
            ->orderBy('order','asc')
            ->with('Marks',function ($query)use ($studentStudyingInformation){
                return $query->where('student_studying_information_id',$studentStudyingInformation->id)
                    ->with('Subject');
            })
            ->with([
                'BaseLevel',
                'BaseSubject.BaseLevelSubjects'=>function($query)use($level){
                    return $query->where('base_level_id',$level->base_level_id)
                        ->with('Rule');
                },
                'YearGradeData' =>function($query)use($studentStudyingInformation){
                    return $query->where('student_studying_information_id',$studentStudyingInformation->id);
                }])
            ->get();

        $yearGradeGeneralInfo = YearGradesGeneralInfo::where('student_studying_information_id',$studentStudyingInformation->id)
            ->first();
        if(is_null($yearGradeGeneralInfo)){
            $yearGradeGeneralInfo = YearGradesGeneralInfo::create([
                'student_studying_information_id' => $studentStudyingInformation->id
            ]);
        }

        $yearGradeTemplateClass = new YearGradeTemplateClass($yearTemplate,$yearGradeGeneralInfo);
        $yearGradeMarks = $yearGradeTemplateClass->process();




        return ApiResponseClass::successResponse([
            'year_grade_marks' => $yearGradeMarks,
            'year_grade_general_info' => $yearGradeGeneralInfo,
        ]);

    }


    public function getForDefinedStudent(Request $request,$level_id,$student_id){
        $user = $request->user();
        $level = Level::with('User.School')->findOrFail($level_id);
        $schoolUser = $level->User;
        $school = $level->User->School;
        $school = School::with('User')->findOrFail($school->id);
        $levelClass = LevelManagementFactory::create($schoolUser);

        $yearSetting = YearSetting::first();

        $studentStudyingInformation = StudentStudyingInformation::where('student_id',$student_id)
            ->where('level_id',$level_id)
            ->whereYear('study_year',$yearSetting->start_date)
            ->first();
        if(is_null($studentStudyingInformation)){
            $studentStudyingInformation = StudentStudyingInformation::create([
                'student_id' => $student_id,
                'school_id' => $school->id,
                'level_id' => $level_id,
                'study_year' => $yearSetting->start_date,
            ]);
        }


        $level = $levelClass->myLevelsById($level_id);
        $yearTemplate = YearGradesTemplate::where('base_level_id',$level->base_level_id)
            ->orderBy('order','asc')
            ->with('Marks',function ($query)use ($studentStudyingInformation){
                return $query->where('student_studying_information_id',$studentStudyingInformation->id)
                    ->with('Subject');
            })
            ->with([
                'BaseLevel',
                'BaseSubject.BaseLevelSubjects'=>function($query)use($level){
                    return $query->where('base_level_id',$level->base_level_id)
                        ->with('Rule');
                },
                'YearGradeData' =>function($query)use($studentStudyingInformation){
                    return $query->where('student_studying_information_id',$studentStudyingInformation->id);
                }])
            ->get();

        $yearGradeGeneralInfo = YearGradesGeneralInfo::where('student_studying_information_id',$studentStudyingInformation->id)
            ->first();
        if(is_null($yearGradeGeneralInfo)){
            $yearGradeGeneralInfo = YearGradesGeneralInfo::create([
                'student_studying_information_id' => $studentStudyingInformation->id
            ]);
        }

        $yearGradeTemplateClass = new YearGradeTemplateClass($yearTemplate,$yearGradeGeneralInfo);
        $yearGradeMarks = $yearGradeTemplateClass->process();




        return ApiResponseClass::successResponse([
            'year_grade_marks' => $yearGradeMarks,
            'year_grade_general_info' => $yearGradeGeneralInfo,
        ]);

//        return ApiResponseClass::successResponse(
//            YearGradesResource::collection($yearTemplate)
//        );

    }


}

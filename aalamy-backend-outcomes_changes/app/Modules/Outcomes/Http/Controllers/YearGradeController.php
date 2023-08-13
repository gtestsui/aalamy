<?php

namespace Modules\Outcomes\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\ClassStudentManagementFactory;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Models\Level;
use Modules\Outcomes\Http\Controllers\Classes\YearGradeTemplateClass;
use Modules\Outcomes\Http\DTO\YearGradesGeneralInfoData;
use Modules\Outcomes\Http\Requests\Mark\GetStudentsMarksByClassIdRequest;
use Modules\Outcomes\Http\Requests\YearGrade\UpdateWritableYearGradeRequest;
use Modules\Outcomes\Http\Resources\YearGradesResource;
use Modules\Outcomes\Models\StudentStudyingInformation;
use Modules\Outcomes\Models\YearGradesData;
use Modules\Outcomes\Models\YearGradesGeneralInfo;
use Modules\Outcomes\Models\YearGradesTemplate;
use Modules\Setting\Models\YearSetting;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;

class YearGradeController extends Controller
{


    public function getForDefinedStudent(Request $request,$level_id,$student_id){
        //test
        $user = $request->user();
        $user->load('School');
        $levelClass = LevelManagementFactory::create($user);
        $student = Student::with([
            'User',
            'BasicInformation',
            'SchoolStudent'=>function($query)use($student_id){
                return $query->with([
                    'School'=>function($query)use($student_id){
                        return $query->with([
                            'ClassStudents'=>function($query)use($student_id){
                                return $query->active()
                                    ->where('student_id',$student_id)
                                    ->with([
                                        'ClassModel.Level',
                                    ]);
                            }
                        ]);
                    }
                ]);
            }
        ])
        ->findOrFail($student_id);

        $yearSetting = YearSetting::first();

        $studentStudyingInformation = StudentStudyingInformation::where('student_id',$student_id)
            ->where('level_id',$level_id)
            ->whereYear('study_year',$yearSetting->start_date)
            ->first();
        if(is_null($studentStudyingInformation)){
            $studentStudyingInformation = StudentStudyingInformation::create([
                'student_id' => $student_id,
                'school_id' => $user->School->id,
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



        $isFailure =
            $yearGradeTemplateClass->getIsFailure() ||
            (
                ((int)$yearGradeGeneralInfo->unexcused_absence_semester_1+(int)$yearGradeGeneralInfo->unexcused_absence_semester_2) >= 16
            );

        return ApiResponseClass::successResponse([
            'year_grade_marks' => $yearGradeMarks,
            'is_failure' => $isFailure,
            'year_grade_general_info' => $yearGradeGeneralInfo,
            'student' => new StudentResource($student),
        ]);

//        return ApiResponseClass::successResponse(
//            YearGradesResource::collection($yearTemplate)
//        );

    }

    public function updateWritableYearGradeData(UpdateWritableYearGradeRequest $request,$year_grade_template_id,$level_id,$student_id){
        $user = $request->user();
        $levelClass = LevelManagementFactory::create($user);
        $level = $levelClass->myLevelsById($level_id);
        $yearSetting = YearSetting::first();

        $studentStudyingInformation = StudentStudyingInformation::where('student_id',$student_id)
            ->where('level_id',$level->id)
            ->whereYear('study_year',$yearSetting->start_date)
            ->firstOrFail();

        $yearGradesTemplate = YearGradesTemplate::findOrFail($year_grade_template_id);

        $yearGradeData = YearGradesData::where('student_studying_information_id',$studentStudyingInformation->id)
            ->where('year_grade_template_id',$year_grade_template_id)->first();
        if(is_null($yearGradeData)){
            $yearGradeData = YearGradesData::create([
                'year_grade_template_id' => $yearGradesTemplate->id,
                'student_studying_information_id' => $studentStudyingInformation->id,
            ]);
        }
        $examDegreeSemester1 = isset($request->exam_degree_semester_1)?$request->exam_degree_semester_1:$yearGradeData->exam_degree_semester_1;
        $examDegreeSemester2 = isset($request->exam_degree_semester_2)?$request->exam_degree_semester_2:$yearGradeData->exam_degree_semester_2;

        $workDegreeSemester1 = isset($request->work_degree_semester_1)?$request->work_degree_semester_1:$yearGradeData->work_degree_semester_1;
        $workDegreeSemester2 = isset($request->work_degree_semester_2)?$request->work_degree_semester_2:$yearGradeData->work_degree_semester_2;

        $totalSemester1 = isset($request->total_semester_1)?$request->total_semester_1:$yearGradeData->total_semester_1;
        $totalSemester2 = isset($request->total_semester_2)?$request->total_semester_2:$yearGradeData->total_semester_2;
        if(isset($request->exam_degree_semester_1) || isset($request->work_degree_semester_1)){
            $totalSemester1 = $examDegreeSemester1 + $workDegreeSemester1;
        }
        if(isset($request->exam_degree_semester_2) || isset($request->work_degree_semester_2)){
            $totalSemester2 = $examDegreeSemester2 + $workDegreeSemester2;
        }

        $yearGradeData->update([
            'exam_degree_semester_1' => $examDegreeSemester1,
            'exam_degree_semester_2' => $examDegreeSemester2,
            'work_degree_semester_1' => $workDegreeSemester1,
            'work_degree_semester_2' => $workDegreeSemester2,
            'total_semester_1' => $totalSemester1,
            'total_semester_2' => $totalSemester2,
        ]);

        return ApiResponseClass::successMsgResponse();
    }


    public function updateGeneralInfo(Request $request,$level_id,$student_id){
        $user = $request->user();
        $levelClass = LevelManagementFactory::create($user);
        $level = $levelClass->myLevelsById($level_id);

        $yearSetting = YearSetting::first();

        $studentStudyingInformation = StudentStudyingInformation::where('student_id',$student_id)
            ->where('level_id',$level->id)
            ->whereYear('study_year',$yearSetting->start_date)
            ->first();

        $yearGradeGeneralInfo = YearGradesGeneralInfo::where('student_studying_information_id',$studentStudyingInformation->id)
            ->first();
        if(is_null($yearGradeGeneralInfo)){
            $yearGradeGeneralInfo = YearGradesGeneralInfo::create([
                'student_studying_information_id' => $studentStudyingInformation->id
            ]);
        }

        $yearGradesGeneralInfoData = YearGradesGeneralInfoData::fromRequest($request);
        $yearGradeGeneralInfo->update($yearGradesGeneralInfoData->initializeForUpdate());
        return ApiResponseClass::successMsgResponse();

    }


    public function getWritableSubjectByBaseLevel(Request $request,$class_id){
        $user = $request->user();
        $classObject = ClassManagementFactory::create($user);
        $class = $classObject->myClassesById($class_id);
        $levelClass = LevelManagementFactory::create($user);
        $level = $levelClass->myLevelsById($class->level_id);
        $writableYearGradesTemplates = YearGradesTemplate::where('base_level_id',$level->base_level_id)
            ->where('its_grand_total',false)
            ->where('its_final_total',false)
            ->where('order','!=',-1)
            ->whereNotNull('writable_subject_name')
            ->get();

        return ApiResponseClass::successResponse($writableYearGradesTemplates);
    }


    //im here doing un good things as the scenario need :)
    public function getStudentsWithMarksInWritableSubject(/*GetStudentsMarksByClassIdRequest*/Request $request,$class_id,$year_grade_template_id){
        //we should change this after detect the permissions for the users whose can use this function
        $user = $request->user();
//        $class = $request->getClass();
        $class = ClassModel::findOrFail($class_id);
        $level = Level::findOrFail($class->level_id);
        $yearGradeTemplate = YearGradesTemplate::findOrFail($year_grade_template_id);
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
            ->with([
                'Student.User',
                'YearGradesData'=>function($query)use($request,$year_grade_template_id){
                    return $query->whereHas('YearGradeTemplate',function ($query)use ($year_grade_template_id){
                        return $query->where('year_grade_template_id',$year_grade_template_id);
                    });
                }
            ])
            ->get();

        return ApiResponseClass::successResponse([
            'studentsStudyingInformation' => $studentsStudyingInformation,
            'yearGradeTemplate' => $yearGradeTemplate,
        ]);
    }

    public function getYearGradeGeneralInfo(Request $request,$level_id,$student_id){

        $user = $request->user();
        $user->load('School');
        $yearSetting = YearSetting::first();

        $studentStudyingInformation = StudentStudyingInformation::where('student_id',$student_id)
            ->where('level_id',$level_id)
            ->whereYear('study_year',$yearSetting->start_date)
            ->first();
        if(is_null($studentStudyingInformation)){
            $studentStudyingInformation = StudentStudyingInformation::create([
                'student_id' => $student_id,
                'school_id' => $user->School->id,
                'level_id' => $level_id,
                'study_year' => $yearSetting->start_date,
            ]);
        }

        $levelClass = LevelManagementFactory::create($user);
        $level = $levelClass->myLevelsById($level_id);


        $yearGradeGeneralInfo = YearGradesGeneralInfo::where('student_studying_information_id',$studentStudyingInformation->id)
            ->first();
        if(is_null($yearGradeGeneralInfo)){
            $yearGradeGeneralInfo = YearGradesGeneralInfo::create([
                'student_studying_information_id' => $studentStudyingInformation->id
            ]);
        }

        return ApiResponseClass::successResponse($yearGradeGeneralInfo);
    }



}

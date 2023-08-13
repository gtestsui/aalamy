<?php


namespace Modules\Outcomes\Http\Controllers\Classes;


use Modules\Level\Models\Level;
use Modules\Level\Models\LevelSubject;
use Modules\Outcomes\Http\DTO\MarkData;
use Modules\Outcomes\Models\Mark;
use Modules\Outcomes\Models\StudentStudyingInformation;
use Modules\Outcomes\Models\YearGradesGeneralInfo;
use Modules\Outcomes\Models\YearGradesTemplate;
use Modules\Setting\Models\YearSetting;

class OutcomesServices
{

    /**
     * @param mixed $studentId
     * @param mixed $schoolId
     * @param Level $level
     * @param YearSetting $yearSetting
     */
    public static function initialize($studentId,$schoolId,$level,$yearSetting){

        $levelSubjects = LevelSubject::where('level_id',$level->id)
            ->with(['Subject.BaseSubject.BaseLevelSubjects'=>function($query)use($level){
                return $query->where('base_level_id',$level->base_level_id)
                    ->with('Rule');
            }])
            ->get();

        //prevent the duplicate if student deleted from a class and the re added
        $foundStudentStudyingInformation = StudentStudyingInformation::query()
            ->where('student_id',$studentId)
            ->where('level_id',$level->id)
            ->where('school_id',$schoolId)
            ->whereYear('study_year',$yearSetting->start_date)
            // ->where('study_year_id',$yearSetting->id)
            ->first();
        if(is_null($foundStudentStudyingInformation)){
            $studentStudyingInformation = StudentStudyingInformation::create([
                'student_id' => $studentId,
                'school_id' => $schoolId,
                'level_id' => $level->id,
                'study_year' => $yearSetting->start_date,
                // 'study_year_id' => $yearSetting->id
            ]);

            YearGradesGeneralInfo::create([
               'student_studying_information_id' => $studentStudyingInformation->id
            ]);

            $yearGradesTemplate = YearGradesTemplate::where('base_level_id',$level->base_level_id)
                ->with('BaseSubject')
                ->get();


            foreach ($levelSubjects as $levelSubject){
                $subjectGradeTemplate = $yearGradesTemplate->where('base_subject_id',$levelSubject->Subject->base_subject_id)
                    ->first();


                if(is_null($subjectGradeTemplate)){
                    $subjectGradeTemplate = $yearGradesTemplate->where('BaseSubject.code',$levelSubject->Subject->code)
                        ->first();
                }

            	if(is_null($subjectGradeTemplate)){
            		continue;
            	}

                Mark::create([
                    'year_grade_template_id' => $subjectGradeTemplate->id,
                    'student_studying_information_id' => $studentStudyingInformation->id,
                    'subject_id' => $levelSubject->Subject->id,
                    'level_subject_id' => $levelSubject->id,
                    'its_one_field' => $levelSubject
                        ->Subject
                        ->BaseSubject
                        ->BaseLevelSubjects[0]
                        ->Rule
                        ->its_one_field,
                ]);
            }

        }
    }


    /**
     * @param Mark $mark
     * @param MarkData $markData
     * @return float|mixed|null
     */
    public static function generateFinalMarks($mark,$markData){
        $exam = isset($markData->exam)?$markData->exam:$mark->exam;
        $quiz = isset($markData->quiz)?$markData->quiz:$mark->quiz;
        $activities_and_Initiatives = isset($markData->activities_and_Initiatives)?$markData->activities_and_Initiatives:$mark->activities_and_Initiatives;
        $verbal = isset($markData->verbal)?$markData->verbal:$mark->verbal;
        $jobs_and_worksheets = isset($markData->jobs_and_worksheets)?$markData->jobs_and_worksheets:$mark->jobs_and_worksheets;

        $finalMark = null;
        if(static::shouldUpdateFinalMark(
            $exam,
            $quiz,
            $activities_and_Initiatives,
            $verbal,
            $jobs_and_worksheets
        )){
//            $mark->load('LevelSubject.Level');
//            $mark->load([
//                'Subject.BaseSubject.BaseLevelSubjects' => function($query)use($mark){
//                    return $query->where('base_level_id',$mark->LevelSubject->Level->base_level_id)
//                        ->with('Rule');
//                }
//            ]);

//            $subjectMaxDegree = $mark->Subject->BaseSubject->BaseLevelSubjects[0]->Rule->max_degree;

            $finalVerbal = self::calculateFinalPercentage(
                $verbal,
                Mark::VERBAL_PERCENTAGE
            );

            $finalJobsAndWorksheets = self::calculateFinalPercentage(
                $jobs_and_worksheets,
                Mark::JOBS_AND_WORK_SHEETS_PERCENTAGE
            );

            $finalActivitiesAndInitiatives = self::calculateFinalPercentage(
                $activities_and_Initiatives,
                Mark::ACTIVITIES_AND_INITIATIVES_PERCENTAGE
            );

            $finalQuiz = self::calculateFinalPercentage(
                $quiz,
                Mark::QUIZ_PERCENTAGE
            );

            $finalExam = self::calculateFinalPercentage(
                $exam,
                Mark::EXAM_PERCENTAGE
            );

            $finalMark = $finalExam+$finalQuiz+$finalActivitiesAndInitiatives+$finalJobsAndWorksheets+$finalVerbal;

        }
        return $finalMark;
    }

    /**
     * @param null|float $exam
     * @param null|float $quiz
     * @param null|float $activities_and_Initiatives
     * @param null|float $verbal
     * @param null|float $jobs_and_worksheets
     * @return bool
     */
    public static function shouldUpdateFinalMark(
        $exam,
        $quiz,
        $activities_and_Initiatives,
        $verbal,
        $jobs_and_worksheets
    ){

        if( !is_null($exam) &&
            !is_null($quiz) &&
            !is_null($activities_and_Initiatives) &&
            !is_null($verbal) &&
            !is_null($jobs_and_worksheets)
        ){
            return true;
        }
        return false;
    }


    public static function calculateFinalPercentage($mark,$percentage){
        return $mark;
//        return ($mark*($percentage/100));
    }


}

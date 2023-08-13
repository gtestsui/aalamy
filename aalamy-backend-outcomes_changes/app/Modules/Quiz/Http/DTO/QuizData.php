<?php


namespace Modules\Quiz\Http\DTO;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class QuizData extends ObjectData
{
    public ?int       $id=null;
    public ?int       $school_id;
    public ?int       $teacher_id;
    public ?int       $educator_id;
    public int        $roster_id;
    public int        $level_subject_id;
//    public ?int       $unit_id;
//    public ?int       $lesson_id;
    public ?array       $unit_ids;
    public ?array       $lesson_ids;
    public int        $mark;
    public bool        $prevent_display_answers;
    public int        $time;
    public string     $name;
    public Carbon     $start_date;
    public Carbon     $end_date;
    public int        $questions_count;
    public int        $hard_questions_count;
    public int        $medium_questions_count;
    public int        $easy_questions_count;

    public int        $hard_questions_mark_percentage;
    public int        $medium_questions_mark_percentage;
    public int        $easy_questions_mark_percentage;

    public static function fromRequest(Request $request): self
    {
        $user = $request->user();
        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
            $user,$request
        );


        return new self([
            'school_id' => $schoolId ,
            'teacher_id' => $teacherId,
            'educator_id' => $educatorId,

            'roster_id' => (int)$request->roster_id,

            'level_subject_id' => (int)$request->level_subject_id,
//            'unit_id' => isset($request->unit_id)?$request->unit_id:null,
//            'lesson_id' => isset($request->lesson_id)?$request->lesson_id:null,

            'unit_ids' => isset($request->unit_ids)?$request->unit_ids:[],
            'lesson_ids' => isset($request->lesson_ids)?$request->lesson_ids:[],

            'name' => $request->name,
            'prevent_display_answers' => isset($request->prevent_display_answers)
                ?(bool)$request->prevent_display_answers
                :false,
            'time' => (int)$request->time,
            'mark' => configFromModule('panel.quiz_full_mark',ApplicationModules::QUIZ_MODULE_NAME)/*(int)$request->mark*/,
            'questions_count' => (int)$request->questions_count,
            'hard_questions_count' => (int)$request->hard_questions_count,
            'medium_questions_count' => (int)$request->medium_questions_count,
            'easy_questions_count' => (int)$request->easy_questions_count,

//            'start_date' => self::generateCarbonObject($request->start_date,true),
            'start_date' => ServicesClass::toTimezone($request->start_date,$request->time_zone,config('panel.timezone')),
//            'end_date' => self::generateCarbonObject($request->end_date,true),
            'end_date' => ServicesClass::toTimezone($request->end_date,$request->time_zone,config('panel.timezone')),


            'hard_questions_mark_percentage' => (int)$request->hard_questions_mark_percentage,
            'medium_questions_mark_percentage' => (int)$request->medium_questions_mark_percentage,
            'easy_questions_mark_percentage' => (int)$request->easy_questions_mark_percentage,

        ]);
    }


}

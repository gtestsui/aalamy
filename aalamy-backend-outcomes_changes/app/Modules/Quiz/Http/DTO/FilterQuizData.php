<?php


namespace Modules\Quiz\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class FilterQuizData extends ObjectData
{
    public ?int       $id=null;
    public ?array      $quizzes_ids;
    public ?int       $level_subject_id;
    public ?int       $unit_id;
    public ?int       $lesson_id;


    public static function fromRequest(Request $request): self
    {
        return new self([
//            'quizzes_ids' => isset($request->quizzes_ids)?$request->quizzes_ids:[] ,
            'quizzes_ids' => isset($request->quizzes_ids)?$request->quizzes_ids:null ,
            'level_subject_id' => isset($request->level_subject_id)?(int)$request->level_subject_id:$request->level_subject_id ,
            'unit_id' => isset($request->unit_id)?(int)$request->unit_id:null,
            'lesson_id' => isset($request->lesson_id)?(int)$request->lesson_id:null,


        ]);
    }

    public static function fromArray(array $array): self
    {
        return new self([
//            'quizzes_ids' => isset($array['quizzes_ids'])?$array['quizzes_ids']:[] ,
            'quizzes_ids' => isset($array['quizzes_ids'])?$array['quizzes_ids']:null ,
            'level_subject_id' => isset($array['level_subject_id'])?(int)$array['level_subject_id']:null ,
            'unit_id' => isset($array['unit_id'])?(int)$array['unit_id']:null ,
            'lesson_id' => isset($array['lesson_id'])?(int)$array['lesson_id']:null ,


        ]);
    }


}

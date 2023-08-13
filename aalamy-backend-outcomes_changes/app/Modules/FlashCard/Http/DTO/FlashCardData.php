<?php


namespace Modules\FlashCard\Http\DTO;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class FlashCardData extends ObjectData
{
    public ?int       $id=null;
    public int        $assignment_id;
    public int        $display_time_in_seconds ;
    public int        $success_percentage ;
    public int          $quiz_time ;
    public string     $quiz_time_type ;
    public ?array     $cards ;
    public ?bool      $generate_quiz ;
//    public ?string      $quiz_type ;
    public ?array      $quiz_types ;
    public ?int      $quiz_question_num ;
    public ?int      $quiz_question_choices_num ;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {

        return new self([
            'assignment_id' => (int)$request->assignment_id,
            'display_time_in_seconds' => (int)$request->display_time_in_seconds,
            'success_percentage' => (int)$request->success_percentage,
            'quiz_time' => (int)$request->quiz_time,
            'quiz_time_type' => configFromModule('panel.default_quiz_time_types',ApplicationModules::FLASH_CARD_MODULE_NAME),
            'cards' => isset($request->cards)?$request->cards:[],
            'generate_quiz' => isset($request->generate_quiz)?(bool)$request->generate_quiz:false,
            'quiz_question_num' => isset($request->quiz_question_num)?(int)$request->quiz_question_num:null,
            'quiz_question_choices_num' => isset($request->quiz_question_choices_num)?(int)$request->quiz_question_choices_num:null,
//            'quiz_type' => $request->quiz_type,
            'quiz_types' => isset($request->quiz_types)?array_unique($request->quiz_types):[],

        ]);
    }


}

<?php


namespace Modules\Quiz\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\QuestionBank\Http\Controllers\Classes\QuestionServices;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

final class QuizStudentAnswerData extends ObjectData
{
    public ?int      $id=null;
    public string    $question;
    public string    $question_type;
    public int       $difficult_level;
    public ?int      $school_id;
    public ?int      $educator_id;
    public ?int      $teacher_id;
    public int       $level_subject_id;
    public ?int      $unit_id;
    public ?int      $lesson_id;

    public ?array    $choices;
    public ?bool     $true_false_status;
    public ?array    $words;
    public ?array    $jumble_sentence_words;
    public ?array    $texts;
    public ?array    $matching_lists;
    public ?array    $ordering_texts;


    public static function fromRequest(Request $request): self
    {
        $user = $request->user();


//        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
//            $user,$request
//        );


        //we used this to sign just the correct chosen type to his variable and ignore others
        list($isMultiChoice,
            $isTrueFalse,
            $isFillInBlank,
            $isJumbleSentence,
            $isFillText,
            $isMatching,
            $isOrdering) = QuestionServices::getCurrentQuestionType($questionType);

     //if we are trying to update there is some fields should not affect
        return new self([
//            'school_id'         => $schoolId,
//            'educator_id'       => $educatorId,
//            'teacher_id'        => $teacherId,

            'question'          => $request->question,
            'question_type'     => $questionType,
            'difficult_level'   => (int)$request->difficult_level,

            'level_subject_id'  => (int)$request->level_subject_id,
            'unit_id'           => isset($request->unit_id)
                ?(int)$request->unit_id
                :null,
            'lesson_id'         => isset($request->lesson_id)
                ?(int)$request->lesson_id
                :null,

            //multi_choice
            'choices' => $isMultiChoice?$request->choices:null,
            //true_false
            'true_false_status' => $isTrueFalse?(bool)$request->true_false_status:null,
            //fill_in_blank
            'words' => $isFillInBlank?$request->words:null,
            //fill_in_blank
            'jumble_sentence_words' => $isJumbleSentence?$request->jumble_sentence_words:null,
            //fill_text
            'texts' => $isFillText?$request->texts:null,
            //matching
            'matching_lists' => $isMatching?$request->matching_lists:null,
            //ordering
            'ordering_texts' => $isOrdering?$request->ordering_texts:null,

        ]);
    }

}

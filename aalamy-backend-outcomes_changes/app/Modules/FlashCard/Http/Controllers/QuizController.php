<?php

namespace Modules\FlashCard\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FlashCard\Http\Controllers\Classes\FlashCardServices;
use Modules\FlashCard\Http\Controllers\Classes\QuizClass;
use Modules\FlashCard\Http\Requests\Quiz\CheckQuizAnswerRequest;
use Modules\FlashCard\Http\Resources\MultiChoiceQuestionResource;
use Modules\FlashCard\Http\Resources\TrueFalseQuestionResource;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Models\FlashCardQuizStudentPercentage;
use Modules\FlashCard\Models\MultiChoiceQuestion;
use Modules\FlashCard\Models\TrueFalseQuestion;

class QuizController extends Controller
{

    public function checkQuizAnswers(CheckQuizAnswerRequest $request,$flash_card_id){
        $user = $request->user();
        $student = $user->Student;
//        return $request->multi_choice_answers;
        $correctAnsweredQuestionCount = 0;
        $flashCard = FlashCard::findOrFail($flash_card_id);
        if(isset($request->multi_choice_answers) && count($request->multi_choice_answers)>0){
            $allQuestionsResult = QuizClass::checkMultiChoiceQuestionAndDisplayAllQuestionsStatuses($request->multi_choice_answers,$correctAnsweredQuestionCount,$flashCard);
            $allQuestionsCount = MultiChoiceQuestion::where('flash_card_id',$flashCard->id)->count();
            $allQuestionResultResponseByQuestionType =  MultiChoiceQuestionResource::collection($allQuestionsResult);
        }

        if(isset($request->true_false_answers) && count($request->true_false_answers)>0){
            $allQuestionsResult = QuizClass::checkTrueFalseQuestionAndDisplayAllQuestionsStatuses($request->true_false_answers,$correctAnsweredQuestionCount,$flashCard);
            $allQuestionsCount = TrueFalseQuestion::where('flash_card_id',$flashCard->id)->count();
            $allQuestionResultResponseByQuestionType =  TrueFalseQuestionResource::collection($allQuestionsResult);
        }

        $studentPercentage = FlashCardServices::calculateStudentPercentage($correctAnsweredQuestionCount,$allQuestionsCount);
        FlashCardServices::refreshStudentPercentageOnFlashCardQuiz(
            $flashCard,$studentPercentage,$student
        );

        return ApiResponseClass::successResponse([
            'success_answers_percentage'=>$studentPercentage.'%',
            'all_questions_result'=>$allQuestionResultResponseByQuestionType
        ]);

    }


}

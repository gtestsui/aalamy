<?php

namespace Modules\FlashCard\Http\Controllers\Classes;


use Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuizAnswers\MultiChoiceQuizAnswerClass;
use Modules\FlashCard\Http\Controllers\Classes\ManageFlashCardQuizAnswers\TrueFalseQuizAnswerClass;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Models\MultiChoiceChoice;
use Modules\FlashCard\Models\MultiChoiceQuestion;
use Modules\FlashCard\Models\TrueFalseQuestion;

class QuizClass
{


    public static function checkMultiChoiceQuestionAndDisplayAllQuestionsStatuses(array $multiChoiceAnswers,&$correctQuestionCount,FlashCard $flashCard){
        $multiChoiceAnswerClass = new MultiChoiceQuizAnswerClass($flashCard,$multiChoiceAnswers,$correctQuestionCount);
        $allQuestionsByFlashCardWithStudentActions = $multiChoiceAnswerClass->prepareResult();
        return $allQuestionsByFlashCardWithStudentActions;
//        $allQuestionsByFlashCard = MultiChoiceQuestion::where('flash_card_id',$flashCard->id)
//            ->with('Card','Choices')
//            ->get();
//        $mapOfUserAnswers = [];//key is question_id and value is selected choice_id by the user
//        foreach ($multiChoiceAnswers as $answer){
//            $mapOfUserAnswers[$answer['question_id']] = (int)$answer['choice_id'];
//        }
//
//        foreach ($allQuestionsByFlashCard as  $question){
//                $selectedChoiceId = isset($mapOfUserAnswers[$question->id])?$mapOfUserAnswers[$question->id]:-1;
//
//                $question->student_has_answered = false;
//                foreach ($question->Choices as $choice){
//                    $choice->selected_by_student = false;
//                    $choice->selected_by_student = false;
//                    $choice->success = false;
//                    if($choice->id == $selectedChoiceId){
//                        $choice->selected_by_student = true;
//                        $question->has_answered = true;
//                    }
//
//                    if($choice->card_id == $question->card_id){
//                        $choice->success = true;
//                        if($choice->selected_by_student)
//                            $correctQuestionCount++;
//                    }
//                }
//
//        }
//        return($allQuestionsByFlashCard);

    }


    public static function checkTrueFalseQuestionAndDisplayAllQuestionsStatuses(array $trueFalseAnswers,&$correctQuestionCount,FlashCard $flashCard){

        $trueFalseAnswerClass = new TrueFalseQuizAnswerClass($flashCard,$trueFalseAnswers,$correctQuestionCount);
        $allQuestionsByFlashCardWithStudentActions = $trueFalseAnswerClass->prepareResult();
        return $allQuestionsByFlashCardWithStudentActions;

//        $allQuestionsByFlashCard = TrueFalseQuestion::where('flash_card_id',$flashCard->id)
//            ->with('QuestionCard','AnswerCard')
//            ->get();
//        $mapOfUserAnswers = [];//key is question_id and value is selected choice_id by the user
//        foreach ($trueFalseAnswers as $answer){
//            $mapOfUserAnswers[$answer['question_id']] = (bool)$answer['answer'];
//        }
//
//
//        foreach ($allQuestionsByFlashCard as  $question){
//            $selectedAnswer = isset($mapOfUserAnswers[$question->id])?$mapOfUserAnswers[$question->id]:null;
//
//            $question->student_has_answered = !is_null($selectedAnswer)?true:false;
//            if($question->question_card_id == $question->answer_card_id){
//                $question->student_answer_is = $selectedAnswer;
//                if($selectedAnswer)
//                    $correctQuestionCount++;
//
//            }else{
//                $question->student_answer_is = !$selectedAnswer;
//                if(!$selectedAnswer)
//                    $correctQuestionCount++;
//            }
//
//        }
//        return($allQuestionsByFlashCard);

    }



    public static function checkMultiChoiceQuestion(array $multiChoiceAnswers,&$correctQuestionCount){
        $multiChoiceQuestionIds = array_column($multiChoiceAnswers,'question_id');
        $multiChoiceQuestions = MultiChoiceQuestion::whereIn('id',$multiChoiceQuestionIds)->get();
        foreach ($multiChoiceAnswers as $index=>$answer){
            $multiChoiceQuestion = $multiChoiceQuestions->where('id',$answer['question_id'])->first();

            $answerStatus = true;
            if(count($answer['choices'])>0){
                $choices = MultiChoiceChoice::whereIn('id',$answer['choices'])
                    ->with('Card')
                    ->get();

                foreach ($choices as $index1=>$choice){
                    if($multiChoiceQuestion->card_id != $choice->card_id)
                        $answerStatus = false;
                }

            }
            if($answerStatus)
                $correctQuestionCount++;
        }
    }

    public static function checkTrueFalseQuestion(array $trueFalseAnswers,&$correctQuestionCount){
        $trueFalseQuestionIds = array_column($trueFalseAnswers,'question_id');
        $trueFalseQuestions = TrueFalseQuestion::whereIn('id',$trueFalseQuestionIds)->get();

        foreach ($trueFalseAnswers as $answer){
//            $trueFalseQuestion = TrueFalseQuestion::findOrFail($answer['question_id']);
            $trueFalseQuestion = $trueFalseQuestions->where('id',$answer['question_id'])->first();

            if($answer['answer']){
                if(($trueFalseQuestion->question_card_id == $trueFalseQuestion->answer_card_id) )
                    $correctQuestionCount++;
            }else{
                if(($trueFalseQuestion->question_card_id != $trueFalseQuestion->answer_card_id) )
                    $correctQuestionCount++;
            }

        }
    }


}

<?php

namespace Modules\FlashCard\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Models\Assignment;
use Modules\Event\Http\Controllers\Classes\ManageEvent\ManageEventInterface;
use Modules\Event\Models\Event;
use Modules\FlashCard\Http\DTO\FlashCardData;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Models\FlashCardQuizStudentPercentage;
use Modules\FlashCard\Models\MultiChoiceChoice;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class FlashCardServices
{

    public static function checkAddFlashCard(Assignment $assignment,User $user,$teacherId=null){
        AssignmentServices::checkUseAssignmentAuthorization($assignment,$user,$teacherId);

    }

    public static function checkUpdateFlashCard(Assignment $assignment,User $user,$teacherId=null){
        AssignmentServices::checkUseAssignmentAuthorization($assignment,$user,$teacherId);

    }

    public static function checkDeleteFlashCard(Assignment $assignment,User $user,$teacherId=null){
        AssignmentServices::checkUseAssignmentAuthorization($assignment,$user,$teacherId);

    }

    public static function checkUseFlashCard(FlashCard $flashCard,User $user,$teacherId=null){
        $assignment = Assignment::find($flashCard->assignment_id);
        AssignmentServices::checkUseAssignmentAuthorization($assignment,$user,$teacherId);

    }


    public static function addMoreThanMultiChoiceChoice(int $questionId,array $cardsIds){
        foreach ($cardsIds as $cardId) {
            MultiChoiceChoice::create([
                'multi_choice_question_id' => $questionId,
                'card_id' => $cardId,
            ]);
        }
    }

//    public static function createManageQuizClassByQuizType($quizType,FlashCard $flashCard,FlashCardData $flashCardData)
//    {
//        $ds = DIRECTORY_SEPARATOR;
//
//        $manageQuizClass = "{$quizType}QuizClass";
//        $manageQuizClassPath = "Modules{$ds}FlashCard{$ds}Http{$ds}Controllers{$ds}Classes{$ds}ManageFlashCardQuiz{$ds}{$manageQuizClass}";
//
//        if(class_exists($manageQuizClassPath)) {
//            $manageQuizClass = new $manageQuizClassPath(
//                $flashCard,
//                $flashCardData->quiz_question_num,
//                $flashCardData->quiz_question_choices_num
//            );
//            return $manageQuizClass;
//        }
//        throw new ErrorMsgException('trying to declare invalid class type ');
//
//    }


    public static function calculateStudentPercentage($correctAnsweredQuestionCount,$allQuestionsCount){
        if($allQuestionsCount==0)
            throw new ErrorMsgException('all questions count is zero');

        return ($correctAnsweredQuestionCount/$allQuestionsCount)*100;

    }

    public static function refreshStudentPercentageOnFlashCardQuiz(FlashCard $flashCard,$studentPercentage,Student $student){
        $flashCardQuizStudentPercentage = FlashCardQuizStudentPercentage::where('student_id',$student->id)
            ->where('flash_card_id',$flashCard->id)
            ->first();
        if(is_null($flashCardQuizStudentPercentage)){
            FlashCardQuizStudentPercentage::create([
                'flash_card_id'=>$flashCard->id,
                'student_id'=>$student->id,
                'percentage'=>$studentPercentage,
            ]);
        }else{
            $flashCardQuizStudentPercentage->update([
                'percentage'=>$studentPercentage
            ]);
        }
    }


}

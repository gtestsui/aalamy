<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionMultiChoice;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankMultiChoice;

class MultiChoiceClassManagement extends BaseManageQuestionAbstract
{

//    private Educator $educator;
//    public function __construct(Educator $educator)
//    {
//        $this->educator = $educator;
//    }

    public function createInBank(QuestionBank $question,QuestionBankData $questionData){
        $arrayForCreate = [];
        $correctChoicesCount = 0;
        foreach ($questionData->choices as $choiceObj){
            if($choiceObj['status'])
                $correctChoicesCount++;
            $arrayForCreate[] = [
                'question_id' => $question->id,
                'choice' => $choiceObj['choice'],
                'status' => $choiceObj['status'],
                'created_at' => Carbon::now(),
            ];
        }
        $this->checkCorrectChoicesCount($correctChoicesCount);
        QuestionBankMultiChoice::insert($arrayForCreate);
    }

    /**
     * @note we have used send parameter by reference because we don't need to declare new variable
     */
    private function checkCorrectChoicesCount(&$correctChoicesCount){
        if($correctChoicesCount >= 2)
            throw new ErrorMsgException(transMsg('the_question_accept_just_one_correct_choice',ApplicationModules::QUESTION_BANK_MODULE_NAME));
    }


    public function updateInBank(QuestionBank $question,QuestionBankData $questionData){
        QuestionBankMultiChoice::where('question_id',$question->id)
            ->delete();
        $this->createInBank($question,$questionData);
    }

    /**
     * @note this is abstract function from parent
     */
    public function getMyQuestionType(){
        return  config('QuestionBank.panel.question_types.multi_choice');
    }


    public function shareWithLibrary(QuestionBank $question,string $shareType){
        $libraryQuestion = $this->storeInLibraryBySharing($question,$shareType);
        $choiceRecords = $question->loadMissing('MultiChoices')->MultiChoices;
        $this->createInLibrary($libraryQuestion,$choiceRecords);

        /*$arrayForCreate = [];
        foreach ($choiceRecords as $choiceRecord){
            $arrayForCreate[] = [
                'library_question_id' => $libraryQuestion->id,
                'choice' => $choiceRecord->choice,
                'status' => $choiceRecord->status,
                'created_at' => Carbon::now(),
            ];
        }
        LibraryQuestionMultiChoice::insert($arrayForCreate);*/

    }


    public function createInLibrary(LibraryQuestion $libraryQuestion,$choiceRecords){
        $arrayForCreate = [];
        foreach ($choiceRecords as $choiceRecord){
            $arrayForCreate[] = [
                'library_question_id' => $libraryQuestion->id,
                'choice' => $choiceRecord->choice,
                'status' => $choiceRecord->status,
                'created_at' => Carbon::now(),
            ];
        }
        LibraryQuestionMultiChoice::insert($arrayForCreate);
    }

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    public function load( $question){
        return $question->load('MultiChoices');
    }

}

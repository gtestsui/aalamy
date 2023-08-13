<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionOrdering;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankOrdering;

class OrderingClassManagement extends BaseManageQuestionAbstract
{

//    private Educator $educator;
//    public function __construct(Educator $educator)
//    {
//        $this->educator = $educator;
//    }

    public function createInBank(QuestionBank $question,QuestionBankData $questionData){

        $arrayForCreate = [];
        $countElementTexts = count($questionData->ordering_texts);
        foreach ($questionData->ordering_texts as $textObj){
            $this->checkOrderTextInRangeOfCountTexts($textObj['order'],$countElementTexts);
            $arrayForCreate[] = [
                'question_id' => $question->id,
                'text' => $textObj['text'],
                'order' => $textObj['order'],
                'created_at' => Carbon::now(),
            ];
        }

        QuestionBankOrdering::insert($arrayForCreate);
    }

    public function checkOrderTextInRangeOfCountTexts(&$order,&$countElementTexts){
        if($order > $countElementTexts)
            throw new ErrorMsgException(transMsg('the_ordering_is_not_suitable',ApplicationModules::QUESTION_BANK_MODULE_NAME));
    }

    public function updateInBank(QuestionBank $question,QuestionBankData $questionData){
        QuestionBankOrdering::where('question_id',$question->id)
            ->delete();
        $this->createInBank($question,$questionData);
    }

    /**
     * @note this is abstract function from parent
     */
    public function getMyQuestionType(){
        return  config('QuestionBank.panel.question_types.ordering');
    }

    public function shareWithLibrary(QuestionBank $question,string $shareType){
        $libraryQuestion = $this->storeInLibraryBySharing($question,$shareType);
        $orderRecords = $question->loadMissing('Ordering')->Ordering;
        $this->createInLibrary($libraryQuestion,$orderRecords);

        /*$arrayForCreate = [];
        foreach ($orderRecords as $orderRecord){
            $arrayForCreate[] = [
                'library_question_id' => $libraryQuestion->id,
                'text' => $orderRecord->text,
                'order' => $orderRecord->order,
                'created_at' => Carbon::now(),
            ];
        }
        LibraryQuestionOrdering::insert($arrayForCreate);*/

    }

    public function createInLibrary(LibraryQuestion $libraryQuestion,$orderRecords){
        $arrayForCreate = [];
        foreach ($orderRecords as $orderRecord){
            $arrayForCreate[] = [
                'library_question_id' => $libraryQuestion->id,
                'text' => $orderRecord->text,
                'order' => $orderRecord->order,
                'created_at' => Carbon::now(),
            ];
        }
        LibraryQuestionOrdering::insert($arrayForCreate);
    }

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    public function load( $question){
        return $question->load('Ordering');
    }

}

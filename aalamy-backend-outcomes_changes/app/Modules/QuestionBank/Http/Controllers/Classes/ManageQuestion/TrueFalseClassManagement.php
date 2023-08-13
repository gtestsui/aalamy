<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;

use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionTrueFalse;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankTrueFalse;

class TrueFalseClassManagement extends BaseManageQuestionAbstract
{

//    private Educator $educator;
//    public function __construct(Educator $educator)
//    {
//        $this->educator = $educator;
//    }

    public function createInBank(QuestionBank $question,QuestionBankData $questionData){
        QuestionBankTrueFalse::create([
            'question_id' => $question->id,
            'status' => $questionData->true_false_status,
        ]);
    }

    public function updateInBank(QuestionBank $question,QuestionBankData $questionData){
        $questionBankTrueFalse = QuestionBankTrueFalse::where('question_id',$question->id)
            ->first();
        $questionBankTrueFalse->update([
            'status' => $questionData->true_false_status
        ]);
    }

    /**
     * @note this is abstract function from parent
     */
    public function getMyQuestionType(){
        return  config('QuestionBank.panel.question_types.true_false');
    }

    public function shareWithLibrary(QuestionBank $question,string $shareType){
        $libraryQuestion = $this->storeInLibraryBySharing($question,$shareType);
        $trueFalseRecord = $question->loadMissing('TrueFalse')->TrueFalse;
        $this->createInLibrary($libraryQuestion,$trueFalseRecord);

        /*LibraryQuestionTrueFalse::create([
            'library_question_id' => $libraryQuestion->id,
            'status' => $trueFalseRecord->status,
        ]);*/

    }

    public function createInLibrary(LibraryQuestion $libraryQuestion,$trueFalseRecord){
        LibraryQuestionTrueFalse::create([
            'library_question_id' => $libraryQuestion->id,
            'status' => $trueFalseRecord->status,
        ]);
    }

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    public function load( $question){
        return $question->load('TrueFalse');
    }

}

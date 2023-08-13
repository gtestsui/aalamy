<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;

use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionJumbleSentence;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankJumbleSentence;

class JumbleSentenceClassManagement extends BaseManageQuestionAbstract
{

//    private Educator $educator;
//    public function __construct(Educator $educator)
//    {
//        $this->educator = $educator;
//    }

    public function createInBank(QuestionBank $question,QuestionBankData $questionData){

        $arrayForCreate = [];
        foreach ($questionData->jumble_sentence_words as $key=>$jumble_sentence_word){

            $arrayForCreate[] = [
                'question_id' => $question->id,
                'word' => $jumble_sentence_word,
                'order' => $key+1,
                'created_at' => Carbon::now(),
            ];
        }

        QuestionBankJumbleSentence::insert($arrayForCreate);
    }

    public function updateInBank(QuestionBank $question,QuestionBankData $questionData){
        QuestionBankJumbleSentence::where('question_id',$question->id)
            ->delete();
        $this->createInBank($question,$questionData);
    }

    /**
     * @note this is abstract function from parent
     */
    public function getMyQuestionType(){
        return  config('QuestionBank.panel.question_types.jumble_sentence');
    }


    public function shareWithLibrary(QuestionBank $question,string $shareType){
        $libraryQuestion = $this->storeInLibraryBySharing($question,$shareType);
        $fillInBlankRecords = $question->loadMissing('FillInBlanks')->FillInBlanks;
        $this->createInLibrary($libraryQuestion,$fillInBlankRecords);

        /*$arrayForCreate = [];
        foreach ($fillInBlankRecords as $fillInBlankRecord){

            $arrayForCreate[] = [
                'library_question_id' => $libraryQuestion->id,
                'word' => $fillInBlankRecord->word,
                'order' => $fillInBlankRecord->order,
                'created_at' => Carbon::now(),
            ];
        }

        LibraryQuestionFillInBlank::insert($arrayForCreate);*/

    }

    public function createInLibrary(LibraryQuestion $libraryQuestion,$fillInBlankRecords){
        $arrayForCreate = [];
        foreach ($fillInBlankRecords as $fillInBlankRecord){

            $arrayForCreate[] = [
                'library_question_id' => $libraryQuestion->id,
                'word' => $fillInBlankRecord->word,
                'order' => $fillInBlankRecord->order,
                'created_at' => Carbon::now(),
            ];
        }

        LibraryQuestionJumbleSentence::insert($arrayForCreate);
    }

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    public function load( $question){
        return $question->load('JumbleSentences');
    }

}

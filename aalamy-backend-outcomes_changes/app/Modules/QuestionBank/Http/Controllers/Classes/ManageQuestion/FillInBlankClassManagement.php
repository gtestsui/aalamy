<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;

use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionFillInBlank;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankFillInBlank;

class FillInBlankClassManagement extends BaseManageQuestionAbstract
{

//    private Educator $educator;
//    public function __construct(Educator $educator)
//    {
//        $this->educator = $educator;
//    }

    public function createInBank(QuestionBank $question,QuestionBankData $questionData){

        $arrayForCreate = [];
        foreach ($questionData->words as $key=>$word){

            $arrayForCreate[] = [
                'question_id' => $question->id,
                'word' => $word,
                'order' => $key+1,
                'created_at' => Carbon::now(),
            ];
        }

        QuestionBankFillInBlank::insert($arrayForCreate);
    }

    public function updateInBank(QuestionBank $question,QuestionBankData $questionData){
        QuestionBankFillInBlank::where('question_id',$question->id)
            ->delete();
        $this->createInBank($question,$questionData);
    }

    /**
     * @note this is abstract function from parent
     */
    public function getMyQuestionType(){
        return  config('QuestionBank.panel.question_types.fill_in_blank');
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

        LibraryQuestionFillInBlank::insert($arrayForCreate);
    }

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    public function load($question){
        return $question->load('FillInBlanks');
    }

}

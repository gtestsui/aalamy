<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;

use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionFillText;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankFillText;

class FillTextClassManagement extends BaseManageQuestionAbstract
{

//    private Educator $educator;
//    public function __construct(Educator $educator)
//    {
//        $this->educator = $educator;
//    }

    public function createInBank(QuestionBank $question,QuestionBankData $questionData){

        $arrayForCreate = [];
        foreach ($questionData->texts as $text){

            $arrayForCreate[] = [
                'question_id' => $question->id,
                'text' => $text,
                'created_at' => Carbon::now(),
            ];
        }

        QuestionBankFillText::insert($arrayForCreate);
    }

    public function updateInBank(QuestionBank $question,QuestionBankData $questionData){
        QuestionBankFillText::where('question_id',$question->id)
            ->delete();
        $this->createInBank($question,$questionData);
    }

    /**
     * @note this is abstract function from parent
     */
    public function getMyQuestionType(){
        return  config('QuestionBank.panel.question_types.fill_text');
    }

    public function shareWithLibrary(QuestionBank $question,string $shareType){
        $libraryQuestion = $this->storeInLibraryBySharing($question,$shareType);
        $fillTextRecords = $question->loadMissing('FillTexts')->FillTexts;
        $this->createInLibrary($libraryQuestion,$fillTextRecords);

       /* $arrayForCreate = [];
        foreach ($fillTextRecords as $fillTextRecord){

            $arrayForCreate[] = [
                'library_question_id' => $libraryQuestion->id,
                'text' => $fillTextRecord->text,
                'created_at' => Carbon::now(),
            ];
        }

        LibraryQuestionFillText::insert($arrayForCreate);*/

    }

    public function createInLibrary(LibraryQuestion $libraryQuestion,$fillTextRecords){
        $arrayForCreate = [];
        foreach ($fillTextRecords as $fillTextRecord){

            $arrayForCreate[] = [
                'library_question_id' => $libraryQuestion->id,
                'text' => $fillTextRecord->text,
                'created_at' => Carbon::now(),
            ];
        }

        LibraryQuestionFillText::insert($arrayForCreate);
    }

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    public function load( $question){
        return $question->load('FillTexts');
    }

}

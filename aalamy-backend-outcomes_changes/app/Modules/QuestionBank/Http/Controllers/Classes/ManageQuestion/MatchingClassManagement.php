<?php

namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;

use Carbon\Carbon;
use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\LibraryQuestionMatchingLeftList;
use Modules\QuestionBank\Models\LibraryQuestionMatchingRightList;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankFillText;
use Modules\QuestionBank\Models\QuestionBankMatchingLeftList;
use Modules\QuestionBank\Models\QuestionBankMatchingRightList;

class MatchingClassManagement extends BaseManageQuestionAbstract
{

//    private Educator $educator;
//    public function __construct(Educator $educator)
//    {
//        $this->educator = $educator;
//    }

    public function createInBank(QuestionBank $question,QuestionBankData $questionData){

        $arrayForCreateRightList = [];
        foreach ($questionData->matching_lists as $objectOfList){

            $left = QuestionBankMatchingLeftList::create([
                'question_id' => $question->id,
                'text' => $objectOfList['left'],
            ]);

            if(isset($objectOfList['right'])){
                $arrayForCreateRightList[] = [
                    'question_id' => $question->id,
                    'left_list_id' => $left->id,
                    'text' => $objectOfList['right'],
                    'created_at' => Carbon::now(),
                ];
            }

        }

        QuestionBankMatchingRightList::insert($arrayForCreateRightList);
    }

    public function updateInBank(QuestionBank $question,QuestionBankData $questionData){
        QuestionBankMatchingLeftList::where('question_id',$question->id)
            ->delete();
        QuestionBankMatchingRightList::where('question_id',$question->id)
            ->delete();
        $this->createInBank($question,$questionData);
    }

    /**
     * @note this is abstract function from parent
     */
    public function getMyQuestionType(){
        return  config('QuestionBank.panel.question_types.matching');
    }

    public function shareWithLibrary(QuestionBank $question,string $shareType){
        $libraryQuestion = $this->storeInLibraryBySharing($question,$shareType);
        $leftListRecords = $question->loadMissing('MatchingLeftList')->MatchingLeftList->load('RightListRecords');
        $this->createInLibrary($libraryQuestion,$leftListRecords);

        /*$arrayForCreateRightList = [];
        foreach ($leftListRecords as $leftListRecord){

            $left = LibraryQuestionMatchingLeftList::create([
                'library_question_id' => $libraryQuestion->id,
                'text' => $leftListRecord->text,
            ]);

            if(isset($leftListRecord->RightListRecords)){
                $arrayForCreateRightList[] = [
                    'library_question_id' => $libraryQuestion->id,
                    'left_list_id' => $left->id,
                    'text' => $leftListRecord->text,
                    'created_at' => Carbon::now(),
                ];
            }

        }
        LibraryQuestionMatchingRightList::insert($arrayForCreateRightList);*/

    }

    public function createInLibrary(LibraryQuestion $libraryQuestion,$leftListRecords){
        $arrayForCreateRightList = [];
        foreach ($leftListRecords as $leftListRecord){

            $left = LibraryQuestionMatchingLeftList::create([
                'library_question_id' => $libraryQuestion->id,
                'text' => $leftListRecord->text,
            ]);

            if(isset($leftListRecord->RightListRecords)){
                $arrayForCreateRightList[] = [
                    'library_question_id' => $libraryQuestion->id,
                    'left_list_id' => $left->id,
                    'text' => $leftListRecord->text,
                    'created_at' => Carbon::now(),
                ];
            }

        }
        LibraryQuestionMatchingRightList::insert($arrayForCreateRightList);
    }

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    public function load( $question){
        return $question->load('MatchingLeftList.RightListRecords');
    }

}

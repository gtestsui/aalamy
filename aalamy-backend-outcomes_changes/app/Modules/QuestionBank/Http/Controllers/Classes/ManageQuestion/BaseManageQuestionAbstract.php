<?php


namespace Modules\QuestionBank\Http\Controllers\Classes\ManageQuestion;


use Modules\QuestionBank\Http\DTO\QuestionBankData;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;

abstract class BaseManageQuestionAbstract
{


    abstract public function createInBank(QuestionBank $question,QuestionBankData $questionData);
    abstract public function updateInBank(QuestionBank $question,QuestionBankData $questionData);

    /**
     * @return string
     * @note return the concreteClass question type
     */
    abstract public function getMyQuestionType();

    /**
     * @param LibraryQuestion|QuestionBank $question
     */
    abstract public function load($question);

    abstract public function createInLibrary(LibraryQuestion $libraryQuestion,$objectFromRecordsOrOneRecord);

    /**
     * @param string $questionType
     * @return bool
     * @note  check if $questionType equal to classType in concrete class
     */
    public function checkCurrentQuestionType(string $questionType){
        if($questionType == $this->getMyQuestionType())
            return true;
        return false;
    }


    /**
     * @return LibraryQuestion
     */
    public function storeInLibraryBySharing(QuestionBank $question,string $shareType){
        return LibraryQuestion::create([
            'question' => $question->question ,
            'question_type' => $question->question_type ,
            'difficult_level' => $question->difficult_level ,
            'share_type' => $shareType ,
            'school_id' => $question->school_id ,
            'educator_id' => $question->educator_id ,
            'teacher_id' => $question->teacher_id ,
            'level_subject_id' => $question->level_subject_id ,
            'unit_id' => $question->unit_id ,
            'lesson_id' => $question->lesson_id ,
        ]);
    }

}

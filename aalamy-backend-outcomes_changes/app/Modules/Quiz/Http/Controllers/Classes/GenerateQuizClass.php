<?php

namespace Modules\Quiz\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\QuestionBank\Http\Controllers\Classes\ManageQuestionByAccountType\BaseManageQuestionByAccountTypeAbstract;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Http\DTO\QuizData;
use Modules\Quiz\Models\Quiz;

class GenerateQuizClass
{


    private  $hardQuestionMark;
    private  $mediumQuestionMark;
    private  $easyQuestionMark;
    private QuizData $quizData;
    private BaseManageQuestionByAccountTypeAbstract $questionBankClass;

    public function __construct(QuizData $quizData,BaseManageQuestionByAccountTypeAbstract $questionBankClass){
        $this->quizData = $quizData;
        $this->questionBankClass = $questionBankClass;
        $this->prepareMarksPercentages();
    }

    private function prepareMarksPercentages(){
        $this->hardQuestionMark = $this->quizData->hard_questions_count != 0
            ?$this->quizData->hard_questions_mark_percentage / $this->quizData->hard_questions_count
            :0;
        $this->mediumQuestionMark = $this->quizData->medium_questions_count != 0
            ?$this->quizData->medium_questions_mark_percentage / $this->quizData->medium_questions_count
            :0;
        $this->easyQuestionMark = $this->quizData->easy_questions_count != 0
            ?$this->quizData->easy_questions_mark_percentage / $this->quizData->easy_questions_count
            :0;
    }

    public function generate(){

        $questions = $this->getQuestionsFromBank();

        $questionCount = count($questions);
        if($questionCount < $this->quizData->questions_count){
            $this->throwErrorWithFoundedQuestionDetails();
        }

        return $questions;
    }



    public function getQuestionsFromBank(){
        DB::enableQueryLog();

        //we have repeated the shared query because the union function wasn't work

        $hard = $this->questionBankClass->getMyQuestionBankQuery()
            ->where('level_subject_id',$this->quizData->level_subject_id)
//            ->when(isset($quizData->unit_id),function ($query){
//                return $query->where('unit_id',$this->quizData->unit_id);
//            })
//            ->when(isset($quizData->lesson_id),function ($query){
//                return $query->where('lesson_id',$this->quizData->lesson_id);
//            })
            ->when(count($this->quizData->unit_ids),function ($query){
                return $query->whereIn('unit_id',$this->quizData->unit_ids);
            })
            ->when(count($this->quizData->lesson_ids),function ($query){
                return $query->whereIn('lesson_id',$this->quizData->lesson_ids);
            })
            ->where('difficult_level',configFromModule('panel.question_difficult_level.height',ApplicationModules::QUESTION_BANK_MODULE_NAME))
            ->inRandomOrder()
            ->limit($this->quizData->hard_questions_count);

        $med = $this->questionBankClass->getMyQuestionBankQuery()
            ->where('level_subject_id',$this->quizData->level_subject_id)
//            ->when(isset($quizData->unit_id),function ($query){
//                return $query->where('unit_id',$this->quizData->unit_id);
//            })
//            ->when(isset($quizData->lesson_id),function ($query){
//                return $query->where('lesson_id',$this->quizData->lesson_id);
//            })
            ->when(count($this->quizData->unit_ids),function ($query){
                return $query->whereIn('unit_id',$this->quizData->unit_ids);
            })
            ->when(count($this->quizData->lesson_ids),function ($query){
                return $query->whereIn('lesson_id',$this->quizData->lesson_ids);
            })
            ->where('difficult_level',configFromModule('panel.question_difficult_level.medium',ApplicationModules::QUESTION_BANK_MODULE_NAME))
            ->inRandomOrder()
            ->limit($this->quizData->medium_questions_count);


        $low = $this->questionBankClass->getMyQuestionBankQuery()
            ->where('level_subject_id',$this->quizData->level_subject_id)
//            ->when(isset($quizData->unit_id),function ($query){
//                return $query->where('unit_id',$this->quizData->unit_id);
//            })
//            ->when(isset($quizData->lesson_id),function ($query){
//                return $query->where('lesson_id',$this->quizData->lesson_id);
//            })
            ->when(count($this->quizData->unit_ids),function ($query){
                return $query->whereIn('unit_id',$this->quizData->unit_ids);
            })
            ->when(count($this->quizData->lesson_ids),function ($query){
                return $query->whereIn('lesson_id',$this->quizData->lesson_ids);
            })
            ->where('difficult_level',configFromModule('panel.question_difficult_level.low',ApplicationModules::QUESTION_BANK_MODULE_NAME))
            ->inRandomOrder()
            ->limit($this->quizData->easy_questions_count);


        $questions = $hard->union($med)
            ->union($low)
//            ->withAllQuestionTypes()
            ->get();

//dd(DB::getQueryLog());

        return $questions;
    }

    private function throwErrorWithFoundedQuestionDetails(){

        $relatedQuestions = $this->questionBankClass->getMyQuestionBankQuery()
            ->where('level_subject_id',$this->quizData->level_subject_id)
//            ->when(isset($quizData->unit_id),function ($query){
//                return $query->where('unit_id',$this->quizData->unit_id);
//            })
//            ->when(isset($quizData->lesson_id),function ($query){
//                return $query->where('lesson_id',$this->quizData->lesson_id);
//            })
            ->when(count($this->quizData->unit_ids),function ($query){
                return $query->whereIn('unit_id',$this->quizData->unit_ids);
            })
            ->when(count($this->quizData->lesson_ids),function ($query){
                return $query->whereIn('lesson_id',$this->quizData->lesson_ids);
            })
            ->get()
            ->groupBy('difficult_level');

        $yourHardQuestionsArray = $relatedQuestions[configFromModule('panel.question_difficult_level.height',ApplicationModules::QUESTION_BANK_MODULE_NAME)]??[];
        $yourMediumQuestionsArray = $relatedQuestions[configFromModule('panel.question_difficult_level.medium',ApplicationModules::QUESTION_BANK_MODULE_NAME)]??[];
        $yourEasyQuestionsArray = $relatedQuestions[configFromModule('panel.question_difficult_level.low',ApplicationModules::QUESTION_BANK_MODULE_NAME)]??[];
        throw new ErrorMsgException(
            "you dont have enough matched questions you have :hard : ".count($yourHardQuestionsArray).",medium :".count($yourMediumQuestionsArray).",easy :".count($yourEasyQuestionsArray).","
        );
    }

    public function prepareQuizQuestionsArrayForCreate($questionsBank,Quiz $quiz){
        $defaultQuestionMark = $this->quizData->mark/$this->quizData->questions_count;
        $arrayForCreate = [];
        foreach ($questionsBank as $question){
            $arrayForCreate[] = [
                'question_id' => $question->id,
                'quiz_id' => $quiz->id,
                'mark' => $this->getQuestionMark($question),
                'created_at' => Carbon::now(),
            ];
        }
        return $arrayForCreate;
    }

    private function getQuestionMark(QuestionBank $questionBank){
        if($questionBank->difficult_level == configFromModule(
            'panel.question_difficult_level.height',ApplicationModules::QUESTION_BANK_MODULE_NAME
          ))
        {
            return $this->hardQuestionMark;
        }

        if ($questionBank->difficult_level == configFromModule(
                'panel.question_difficult_level.medium',ApplicationModules::QUESTION_BANK_MODULE_NAME
            ))
        {
            return $this->mediumQuestionMark;

        }

        if ($questionBank->difficult_level == configFromModule(
                'panel.question_difficult_level.low',ApplicationModules::QUESTION_BANK_MODULE_NAME
            ))
        {
            return $this->easyQuestionMark;

        }
        throw new ErrorMsgException('some thing went wrong while initialize marks');

    }



}

<?php

namespace Modules\Quiz\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;

class QuizSettingClass
{

    private $percentage;
    private $request;
    private $allQuestionsCount;

    private $hardQuestionsCount;
    private $mediumQuestionsCount;
    private $easyQuestionsCount;

    private $hardQuestionsMarkPercentage;
    private $mediumQuestionsMarkPercentage;
    private $easyQuestionsMarkPercentage;

    public function __construct($request){
        $this->allQuestionsCount = $request->questions_count;
        $this->request = $request;
        $this->percentage = 100;
    }

    public function __get($key){
        if(isset($this->{$key}))
            return $this->{$key};
        throw new ErrorMsgException('trying to get non exists property from quiz setting');
    }


    public function checkQuestionsDetails(){

//        if(isset($request->hard_questions_count)){
            $this->hardQuestionsCount = $this->request->hard_questions_count;
            $this->mediumQuestionsCount = $this->request->medium_questions_count;
            $this->easyQuestionsCount = $this->request->easy_questions_count;
            $this->checkValidQuestionsCountWithDetailsCount();
//        }else{

            $this->hardQuestionsMarkPercentage = $this->request->hard_questions_mark_percentage;
            $this->mediumQuestionsMarkPercentage = $this->request->medium_questions_mark_percentage;
            $this->easyQuestionsMarkPercentage = $this->request->easy_questions_mark_percentage;
            $this->checkValidQuestionsMarkDetailsPercentages();
//            $this->convertFromPercentagesToCounts();

//        }

    }

    private function checkValidQuestionsCountWithDetailsCount(){
        $sum = $this->hardQuestionsCount
            + $this->mediumQuestionsCount
            +$this->easyQuestionsCount;

        if($sum!= $this->allQuestionsCount)
            throw new ErrorMsgException('invalid question counts with its details count');
    }

    private function checkValidQuestionsMarkDetailsPercentages(){
        $sum = $this->hardQuestionsMarkPercentage
            + $this->mediumQuestionsMarkPercentage
            +$this->easyQuestionsMarkPercentage;

        if($sum != $this->percentage)
            throw new ErrorMsgException('invalid question counts with its details count');

    }

    private function convertFromPercentagesToCounts(){
        $hardCount = floor(
            ($this->hardQuestionsPercentage/100)*$this->allQuestionsCount
        );

        $mediumCount = ceil(
            ($this->mediumQuestionsPercentage/100)*$this->allQuestionsCount
        );

        $easyCount = $this->allQuestionsCount-($hardCount+$mediumCount);

        $this->hardQuestionsCount = $hardCount;
        $this->mediumQuestionsCount = $mediumCount;
        $this->easyQuestionsCount = $easyCount;


    }


}

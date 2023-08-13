<?php

namespace Modules\QuestionBank\Traits\ModelRelations\QuestionBank;


use Modules\QuestionBank\Models\QuestionBank;
use \Modules\QuestionBank\Models\QuestionBankMatchingRightList;
use Modules\Quiz\Models\QuizQuestionMatchingAnswer;


trait MatchingLeftListRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(QuestionBank::class,'question_id');
    }

    public function RightListRecords(){
        return $this->hasMany(QuestionBankMatchingRightList::class,'left_list_id');
    }

    public function QuizQuestionMatchingAnswers(){
        return $this->hasMany(QuizQuestionMatchingAnswer::class,'left_list_id');
    }

}

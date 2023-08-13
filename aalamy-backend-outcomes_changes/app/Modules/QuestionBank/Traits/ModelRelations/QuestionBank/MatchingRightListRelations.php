<?php

namespace Modules\QuestionBank\Traits\ModelRelations\QuestionBank;


use Modules\QuestionBank\Models\QuestionBank;
use Modules\QuestionBank\Models\QuestionBankMatchingLeftList;
use Modules\Quiz\Models\QuizQuestionMatchingAnswer;


trait MatchingRightListRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(QuestionBank::class,'question_id');
    }

    public function LeftList(){
        return $this->belongsTo(QuestionBankMatchingLeftList::class,'left_list_id');
    }

    public function QuizQuestionMatchingAnswers(){
        return $this->hasMany(QuizQuestionMatchingAnswer::class,'right_list_id');
    }

}

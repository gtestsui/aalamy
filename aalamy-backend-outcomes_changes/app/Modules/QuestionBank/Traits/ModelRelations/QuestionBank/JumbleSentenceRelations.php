<?php

namespace Modules\QuestionBank\Traits\ModelRelations\QuestionBank;


use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\QuizQuestionJumbleSentenceAnswer;


trait JumbleSentenceRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(QuestionBank::class,'question_id');
    }

    public function QuizQuestionJumbleSentenceAnswers(){
        return $this->hasMany(QuizQuestionJumbleSentenceAnswer::class,'jumble_sentence_id');
    }

}

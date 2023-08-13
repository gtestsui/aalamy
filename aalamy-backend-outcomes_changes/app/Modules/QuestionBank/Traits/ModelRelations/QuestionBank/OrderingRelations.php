<?php

namespace Modules\QuestionBank\Traits\ModelRelations\QuestionBank;


use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\QuizQuestionOrderingAnswer;


trait OrderingRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(QuestionBank::class,'library_question_id');
    }

    public function QuizQuestionOrderingAnswers(){
        return $this->hasMany(QuizQuestionOrderingAnswer::class,'order_text_id');
    }

}

<?php

namespace Modules\QuestionBank\Traits\ModelRelations\QuestionBank;


use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\QuizQuestionMultiChoiceAnswer;


trait MultiChoiceRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo(QuestionBank::class,'question_id');
    }

    public function QuizQuestionMultiChoiceAnswers(){
        return $this->hasMany(QuizQuestionMultiChoiceAnswer::class,'choice_id');
    }

}

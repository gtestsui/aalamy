<?php

namespace Modules\Quiz\Traits\ModelRelations;


use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizQuestionStudentAnswer;

trait QuizQuestionRelations
{

    //Relations
    public function Quiz(){
        return $this->belongsTo(Quiz::class,'quiz_id');
    }

    public function QuestionBank(){
        return $this->belongsTo(QuestionBank::class,'question_id');
    }

    public function QuizQuestionStudentAnswers(){
        return $this->hasMany(QuizQuestionStudentAnswer::class,'quiz_question_id');
    }



}

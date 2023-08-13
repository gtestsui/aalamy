<?php

namespace Modules\Quiz\Traits\ModelRelations;


use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizQuestionFillInBlankAnswer;
use Modules\Quiz\Models\QuizQuestionFillTextAnswer;
use Modules\Quiz\Models\QuizQuestionJumbleSentenceAnswer;
use Modules\Quiz\Models\QuizQuestionMatchingAnswer;
use Modules\Quiz\Models\QuizQuestionMultiChoiceAnswer;
use Modules\Quiz\Models\QuizQuestionOrderingAnswer;
use Modules\Quiz\Models\QuizQuestionTrueFalseAnswer;
use Modules\Quiz\Models\QuizStudent;

trait QuestionStudentAnswerRelations
{

    //Relations
    public function QuizStudent(){
        return $this->belongsTo(QuizStudent::class,'quiz_student_id');
    }

    public function QuizQuestion(){
        return $this->belongsTo(QuizQuestion::class,'quiz_question_id');
    }

    public function FillInBlankAnswers(){
        return $this->hasMany(QuizQuestionFillInBlankAnswer::class,'quiz_question_student_answer_id');
    }

    public function FillTextAnswer(){
        return $this->hasOne(QuizQuestionFillTextAnswer::class,'quiz_question_student_answer_id');
    }


    public function JumbleSentenceAnswers(){
        return $this->hasMany(QuizQuestionJumbleSentenceAnswer::class,'quiz_question_student_answer_id');
    }

    public function MatchingAnswers(){
        return $this->hasMany(QuizQuestionMatchingAnswer::class,'quiz_question_student_answer_id');
    }

    public function MultiChoiceAnswer(){
        return $this->hasOne(QuizQuestionMultiChoiceAnswer::class,'quiz_question_student_answer_id');
    }

    public function OrderingAnswers(){
        return $this->hasMany(QuizQuestionOrderingAnswer::class,'quiz_question_student_answer_id');
    }

    public function TrueFalseAnswer(){
        return $this->hasOne(QuizQuestionTrueFalseAnswer::class,'quiz_question_student_answer_id');
    }








}

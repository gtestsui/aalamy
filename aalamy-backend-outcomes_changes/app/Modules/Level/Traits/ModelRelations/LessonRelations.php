<?php

namespace Modules\Level\Traits\ModelRelations;


use Modules\Assignment\Models\Assignment;
use Modules\LearningResource\Models\LearningResource;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\Quiz;

trait LessonRelations
{

    //Relations
    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

    public function Unit(){
        return $this->belongsTo('Modules\Level\Models\Unit','unit_id');
    }

    public function Assignments(){
        return $this->hasMany(Assignment::class,'lesson_id');
    }

    public function QuestionBanks(){
        return $this->hasMany(QuestionBank::class,'lesson_id');
    }

    public function LibraryQuestions(){
        return $this->hasMany(LibraryQuestion::class,'lesson_id');
    }

    public function LearningResources(){
        return $this->hasMany(LearningResource::class,'lesson_id');
    }

    public function Quizzes(){
        return $this->hasMany(Quiz::class,'lesson_id');
    }


}

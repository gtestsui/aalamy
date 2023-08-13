<?php

namespace Modules\Level\Traits\ModelRelations;


use Modules\Assignment\Models\Assignment;
use Modules\ClassModule\Models\ClassModel;
use Modules\LearningResource\Models\LearningResource;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\Quiz;

trait UnitRelations
{

    //Relations
    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

    public function LevelSubject(){
        return $this->belongsTo('Modules\Level\Models\LevelSubject','level_subject_id');
    }

    public function Assignments(){
        return $this->hasMany(Assignment::class,'unit_id');
    }

    public function Lessons(){
        return $this->hasMany(Lesson::class,'unit_id');
    }

    public function QuestionBanks(){
        return $this->hasMany(QuestionBank::class,'unit_id');
    }

    public function LibraryQuestions(){
        return $this->hasMany(LibraryQuestion::class,'unit_id');
    }

    public function LearningResources(){
        return $this->hasMany(LearningResource::class,'unit_id');
    }

    public function Quizzes(){
        return $this->hasMany(Quiz::class,'unit_id');
    }



}

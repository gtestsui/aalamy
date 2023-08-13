<?php

namespace Modules\Level\Traits\ModelRelations;


use Modules\Assignment\Models\Assignment;
use Modules\ClassModule\Models\ClassModel;
use Modules\LearningResource\Models\LearningResource;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\Mark\Models\GradeBook;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\Quiz;

trait LevelSubjectRelations
{

    //Relations
    public function Level(){
        return $this->belongsTo('Modules\Level\Models\Level','level_id');
    }
    public function LevelEvenItsDeleted(){
        return $this->Level()->withDeletedItems();
    }

    public function Subject(){
        return $this->belongsTo('Modules\Level\Models\Subject','subject_id');
    }
    public function SubjectEvenItsDeleted(){
        return $this->Subject()->withDeletedItems();
    }

    public function ClassInfos(){
        return $this->hasMany('Modules\ClassModule\Models\ClassInfo','level_subject_id');
    }

    public function Assignments(){
        return $this->hasMany(Assignment::class,'level_subject_id');
    }

    public function Units(){
        return $this->hasMany(Unit::class,'level_subject_id');
    }

    public function QuestionBanks(){
        return $this->hasMany(QuestionBank::class,'level_subject_id');
    }

    public function LibraryQuestions(){
        return $this->hasMany(LibraryQuestion::class,'level_subject_id');
    }

    public function LearningResources(){
        return $this->hasMany(LearningResource::class,'level_subject_id');
    }

    public function GradeBooks(){
        return $this->hasMany(GradeBook::class,'level_subject_id');
    }

    public function Quizzes(){
        return $this->hasMany(Quiz::class,'level_subject_id');
    }

}

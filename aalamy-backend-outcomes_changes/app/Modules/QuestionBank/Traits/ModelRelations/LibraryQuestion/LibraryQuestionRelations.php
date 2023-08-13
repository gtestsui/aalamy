<?php

namespace Modules\QuestionBank\Traits\ModelRelations\LibraryQuestion;


use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\QuestionBank\Models\LibraryQuestionFillInBlank;
use Modules\QuestionBank\Models\LibraryQuestionFillText;
use Modules\QuestionBank\Models\LibraryQuestionJumbleSentence;
use Modules\QuestionBank\Models\LibraryQuestionMatchingLeftList;
use Modules\QuestionBank\Models\LibraryQuestionMatchingRightList;
use Modules\QuestionBank\Models\LibraryQuestionMultiChoice;
use Modules\QuestionBank\Models\LibraryQuestionOrdering;
use Modules\QuestionBank\Models\LibraryQuestionTrueFalse;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait LibraryQuestionRelations
{

    //Relations
    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }

    public function Educator(){
        return $this->belongsTo(Educator::class,'educator_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function LevelSubject(){
        return $this->belongsTo(LevelSubject::class,'level_subject_id');
    }

    public function Unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }

    public function Lesson(){
        return $this->belongsTo(Lesson::class,'lesson_id');
    }

    public function FillInBlanks(){
        return $this->hasMany(LibraryQuestionFillInBlank::class,'library_question_id');
    }

    public function FillTexts()
    {
        return $this->hasMany(LibraryQuestionFillText::class, 'library_question_id');
    }

    public function JumbleSentences()
    {
        return $this->hasMany(LibraryQuestionJumbleSentence::class, 'library_question_id');
    }

    public function MatchingLeftList(){
        return $this->hasMany(LibraryQuestionMatchingLeftList::class,'library_question_id');
    }

    public function MatchingRightList(){
        return $this->hasMany(LibraryQuestionMatchingRightList::class,'library_question_id');
    }

    public function MultiChoices(){
        return $this->hasMany(LibraryQuestionMultiChoice::class,'library_question_id');
    }

    public function Ordering(){
        return $this->hasMany(LibraryQuestionOrdering::class,'library_question_id');
    }

    public function TrueFalse(){
        return $this->hasOne(LibraryQuestionTrueFalse::class,'library_question_id');
    }

}

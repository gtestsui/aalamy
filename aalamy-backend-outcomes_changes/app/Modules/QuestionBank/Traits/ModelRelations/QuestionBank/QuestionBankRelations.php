<?php

namespace Modules\QuestionBank\Traits\ModelRelations\QuestionBank;


use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\QuestionBank\Models\QuestionBankFillInBlank;
use Modules\QuestionBank\Models\QuestionBankFillText;
use Modules\QuestionBank\Models\QuestionBankJumbleSentence;
use Modules\QuestionBank\Models\QuestionBankMatchingLeftList;
use Modules\QuestionBank\Models\QuestionBankMatchingRightList;
use Modules\QuestionBank\Models\QuestionBankMultiChoice;
use Modules\QuestionBank\Models\QuestionBankOrdering;
use Modules\QuestionBank\Models\QuestionBankTrueFalse;
use Modules\Quiz\Models\QuizQuestion;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait QuestionBankRelations
{

    //Relations
    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }
    public function SchoolEvenItsDeleted(){
        return $this->School()->withDeletedItems();
    }


    public function Educator(){
        return $this->belongsTo(Educator::class,'educator_id');
    }
    public function EducatorEvenItsDeleted(){
        return $this->Educator()->withDeletedItems();
    }


    public function Teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }
    public function TeacherEvenItsDeleted(){
        return $this->Teacher()->withDeletedItems();
    }

    public function LevelSubject(){
        return $this->belongsTo(LevelSubject::class,'level_subject_id');
    }
    public function LevelSubjectEvenItsDeleted(){
        return $this->LevelSubject()->withDeletedItems();
    }

    public function Unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }
    public function UnitEvenItsDeleted(){
        return $this->Unit()->withDeletedItems();
    }


    public function Lesson(){
        return $this->belongsTo(Lesson::class,'lesson_id');
    }
    public function LessonEvenItsDeleted(){
        return $this->Lesson()->withDeletedItems();
    }

    public function FillInBlanks(){
        return $this->hasMany(QuestionBankFillInBlank::class,'question_id');
    }

    public function FillTexts()
    {
        return $this->hasMany(QuestionBankFillText::class, 'question_id');
    }

    public function JumbleSentences()
    {
        return $this->hasMany(QuestionBankJumbleSentence::class, 'question_id');
    }

    public function MatchingLeftList(){
        return $this->hasMany(QuestionBankMatchingLeftList::class,'question_id');
    }

    public function MatchingRightList(){
        return $this->hasMany(QuestionBankMatchingRightList::class,'question_id');
    }

    public function MultiChoices(){
        return $this->hasMany(QuestionBankMultiChoice::class,'question_id');
    }

    public function Ordering(){
        return $this->hasMany(QuestionBankOrdering::class,'question_id');
    }

    public function TrueFalse(){
        return $this->hasOne(QuestionBankTrueFalse::class,'question_id');
    }

    public function QuizQuestions(){
        return $this->hasMany(QuizQuestion::class,'question_id');
    }

}

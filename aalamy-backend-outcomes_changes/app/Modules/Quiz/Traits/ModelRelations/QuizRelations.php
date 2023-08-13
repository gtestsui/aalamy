<?php

namespace Modules\Quiz\Traits\ModelRelations;


use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\Mark\Models\GradeBookQuiz;
use Modules\Quiz\Models\QuizLesson;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizStudent;
use Modules\Quiz\Models\QuizUnit;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait QuizRelations
{

    //Relations
    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }
    public function SchoolEvenItsDeleted(){
        return $this->School()->withDeletedItems();
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }
    public function TeacherEvenItsDeleted(){
        return $this->Teacher()->withDeletedItems();
    }

    public function Educator(){
        return $this->belongsTo(Educator::class,'educator_id');
    }
    public function EducatorEvenItsDeleted(){
        return $this->Educator()->withDeletedItems();
    }

    public function Roster(){
        return $this->belongsTo(Roster::class,'roster_id');
    }
    public function RosterEvenItsDeleted(){
        return $this->Roster()->withDeletedItems();
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

    public function Questions(){
        return $this->hasMany(QuizQuestion::class,'quiz_id');
    }

    public function QuizStudents(){
        return $this->hasMany(QuizStudent::class,'quiz_id');
    }

    public function GradeBookQuizzes(){
        return $this->hasMany(GradeBookQuiz::class,'quiz_id');
    }

    public function QuizUnits(){
        return $this->hasMany(QuizUnit::class,'quiz_id');
    }

    public function QuizLessons(){
        return $this->hasMany(QuizLesson::class,'quiz_id');
    }

}

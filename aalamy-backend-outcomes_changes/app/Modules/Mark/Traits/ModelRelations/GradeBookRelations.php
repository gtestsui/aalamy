<?php

namespace Modules\Mark\Traits\ModelRelations;


use Modules\Level\Models\LevelSubject;
use Modules\Mark\Models\GradeBookExternalMark;
use Modules\Mark\Models\GradeBookQuiz;
use Modules\Mark\Models\GradeBookRosterAssignment;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait GradeBookRelations
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


    public function Roster(){
        return $this->belongsTo(Roster::class,'roster_id');
    }

    public function LevelSubject(){
        return $this->belongsTo(LevelSubject::class,'level_subject_id');
    }


    public function GradeBookQuizzes(){
        return $this->hasMany(GradeBookQuiz::class,'grade_book_id');
    }

    public function GradeBookRosterAssignments(){
        return $this->hasMany(GradeBookRosterAssignment::class,'grade_book_id');
    }

    public function GradeBookExternalMarks(){
        return $this->hasMany(GradeBookExternalMark::class,'grade_book_id');
    }

}

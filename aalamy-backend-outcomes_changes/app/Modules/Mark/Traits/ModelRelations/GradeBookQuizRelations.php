<?php

namespace Modules\Mark\Traits\ModelRelations;


use Modules\Level\Models\LevelSubject;
use Modules\Mark\Models\GradeBook;
use Modules\Mark\Models\GradeBookExternalMark;
use Modules\Mark\Models\GradeBookQuiz;
use Modules\Mark\Models\GradeBookRosterAssignment;
use Modules\Quiz\Models\Quiz;
use Modules\Roster\Models\Roster;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

trait GradeBookQuizRelations
{

    //Relations
    public function GradeBook(){
        return $this->belongsTo(GradeBook::class,'grade_book_id');
    }

    public function Quiz(){
        return $this->belongsTo(Quiz::class,'quiz_id');
    }


}

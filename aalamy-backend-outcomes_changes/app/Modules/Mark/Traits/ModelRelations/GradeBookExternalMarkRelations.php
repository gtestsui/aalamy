<?php

namespace Modules\Mark\Traits\ModelRelations;


use Modules\Level\Models\LevelSubject;
use Modules\Mark\Models\GradeBook;
use Modules\Mark\Models\GradeBookExternalMark;
use Modules\Mark\Models\GradeBookQuiz;
use Modules\Mark\Models\GradeBookRosterAssignment;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

trait GradeBookExternalMarkRelations
{

    //Relations
    public function GradeBook(){
        return $this->belongsTo(GradeBook::class,'grade_book_id');
    }

    public function Student(){
        return $this->belongsTo(Student::class,'student_id');
    }


}

<?php

namespace Modules\Roster\Traits\ModelRelations;


use Modules\Mark\Models\GradeBook;
use Modules\Quiz\Models\Quiz;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\EducatorStudentRequest\Models\EducatorRosterStudentRequest;
use Modules\Roster\Models\RosterStudent;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait RosterRelations
{

    //Relations
    public function ClassInfo(){
        return $this->belongsTo('Modules\ClassModule\Models\ClassInfo','class_info_id');
    }


    public function CreatedByTeacher(){
        return $this->belongsTo(Teacher::class,'created_by_teacher_id');
    }

    public function CreatedBySchool(){
        return $this->belongsTo(School::class,'created_by_school_id');
    }

    public function CreatedByEducator(){
        return $this->belongsTo(Educator::class,'created_by_educator_id');
    }

    public function EducatorRosterStudentRequests(){
        return $this->hasMany(EducatorRosterStudentRequest::class,'roster_id');
    }

    public function RosterStudents(){
        return $this->hasMany(RosterStudent::class,'roster_id');
    }

    public function RosterAssignments(){
        return $this->hasMany(RosterAssignment::class,'roster_id');
    }

    public function AvailableRosterAssignments(){
        return $this->hasMany(RosterAssignment::class,'roster_id')
//            ->isLocked(false)
            ->isHidden(false);
    }

    public function GradeBooks(){
        return $this->hasMany(GradeBook::class,'roster_id');
    }

    public function Quizzes(){
        return $this->hasMany(Quiz::class,'roster_id');
    }


}

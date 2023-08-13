<?php

namespace Modules\TeacherPermission\Traits\ModelRelations;


use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\Mark\Models\GradeBookQuiz;
use Modules\Quiz\Models\QuizQuestion;
use Modules\Quiz\Models\QuizStudent;
use Modules\Roster\Models\Roster;
use Modules\TeacherPermission\Models\Permission;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait TeacherPermissionRelations
{

    //Relations
    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function Educator(){
        return $this->belongsTo(Educator::class,'educator_id');
    }

    public function Permission(){
        return $this->belongsTo(Permission::class,'permission_id');
    }

}

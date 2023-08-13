<?php

namespace Modules\Feedback\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Feedback\Models\FeedbackAboutStudentAttendance;
use Modules\Feedback\Models\FeedbackAboutStudentMark;
use Modules\Roster\Models\Roster;

trait FeedbackAboutStudentRelations
{

    //Relations
    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator','educator_id');
    }

    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    public function Teacher(){
        return $this->belongsTo('Modules\User\Models\Teacher','teacher_id');
    }

    public function Student(){
        return $this->belongsTo('Modules\User\Models\Student','student_id');
    }

    public function StudentAttendances(){
        return $this->hasMany(FeedbackAboutStudentAttendance::class,'feedback_id');
    }

    public function StudentMarks(){
        return $this->hasMany(FeedbackAboutStudentMark::class,'feedback_id');
    }

    public function Files(){
        return $this->hasMany('Modules\Feedback\Models\FeedbackAboutStudentFile','feedback_id');
    }

    public function Images(){
        return $this->hasMany('Modules\Feedback\Models\FeedbackAboutStudentImage','feedback_id');
    }


}

<?php

namespace Modules\Notification\Traits\ModelRelations;


use Modules\Assignment\Models\Assignment;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait ManualNotificationRelations
{

    //Relations
//    public function User(){
//        return $this->belongsTo('Modules\User\Models\User','user_id');
//    }

    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }

    public function Educator(){
        return $this->belongsTo(Educator::class,'educator_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function Receivers(){
        return $this->hasMany('Modules\Notification\Models\ManualNotificationReceiver','manual_notification_id');
    }


}

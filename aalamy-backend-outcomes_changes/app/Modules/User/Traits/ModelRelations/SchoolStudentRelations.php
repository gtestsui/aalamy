<?php

namespace Modules\User\Traits\ModelRelations;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\Assignment\Models\Assignment;
use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Event\Models\Event;
use Modules\Event\Models\EventTargetUser;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\Level;
use Modules\Level\Models\Subject;
use Modules\Notification\Models\FirebaseToken;
use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\SubscriptionPlan\Models\UserSubscription;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\EducatorStudent;

trait SchoolStudentRelations
{

    //Relations
    public function Student(){
        return $this->belongsTo('Modules\User\Models\Student','student_id');
    }

    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    //relation without directly foreign key (invalid)
//    public function ClassStudent($t){
//        return $this->hasMany('Modules\ClassModule\Models\ClassStudent','student_id','student_id')
//            ->where('school_id',1);
//    }
}

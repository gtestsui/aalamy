<?php

namespace Modules\User\Traits\ModelRelations;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\Assignment\Models\Assignment;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Event\Models\Event;
use Modules\Event\Models\EventTargetUser;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\Level;
use Modules\Level\Models\Subject;
use Modules\Meeting\Models\MeetingTargetUser;
use Modules\Notification\Models\FirebaseToken;
use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\SchoolInvitation\Models\SchoolTeacherInvitation;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\SubscriptionPlan\Models\UserSubscription;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\EducatorStudent;

trait ParentRelations
{

    //Relations
    public function User(){
        return $this->belongsTo('Modules\User\Models\User');
    }

    public function ParentStudents(){
        return $this->hasMany('Modules\User\Models\ParentStudent','parent_id');
    }

    public function TargetUsers(){
        return $this->hasMany(EventTargetUser::class,'parent_id');
    }

    public function MeetingTargetUsers(){
        return $this->hasMany(MeetingTargetUser::class,'parent_id');
    }

}

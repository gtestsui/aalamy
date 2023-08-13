<?php

namespace Modules\User\Traits\ModelRelations;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\ContactUs\Models\ContactUs;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\LearningResource\Models\Topic;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\Level;
use Modules\Level\Models\Subject;
use Modules\Level\Models\Unit;
use Modules\Notification\Models\FirebaseToken;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\SubscriptionPlan\Models\UserSubscription;
use Modules\User\Http\Controllers\Classes\UserServices;

trait UserRelations
{

    //Relations
    public function Student(){
        return $this->hasOne('Modules\User\Models\Student');
    }

    public function Parent(){
        return $this->hasOne('Modules\User\Models\ParentModel');
    }

    public function Educator(){
        return $this->hasOne('Modules\User\Models\Educator');
    }

    public function School(){
        return $this->hasOne('Modules\User\Models\School');
    }

    public function LoggedDevices(){
        return $this->hasMany('Modules\User\Models\LoggedDevice');
    }

    public function ForgetPassword(){
        return $this->hasOne('Modules\User\Models\ForgetPassword');
    }

    public function Teachers(){
        return $this->hasMany('Modules\User\Models\Teacher','user_id');
    }

    public function Address(){
        return $this->belongsTo('Modules\Address\Models\Address','address_id');
    }

    public function AccountConfirmationSetting(){
        return $this->hasOne('Modules\User\Models\AccountConfirmationCodeSetting','user_id');
    }

    public function DiscussionCornerPosts(){
        return $this->hasMany(DiscussionCornerPost::class,'user_id');
    }

    public function DiscussionCornerSurveys(){
        return $this->hasMany(DiscussionCornerSurvey::class,'user_id');
    }

    //who create the user
    public function CreatedBy(){
        return $this->belongsTo('Modules\User\Models\User','created_by');
    }

    public function SurveyUsers(){
        return $this->hasMany('Modules\DiscussionCorner\Models\DiscussionCornerSurveyUser','survey_id');
    }

    public function Firebase(){
        return $this->hasMany(FirebaseToken::class,'user_id');
    }

    public function Lessons(){
        return $this->hasMany(Lesson::class,'user_id');
    }

    public function Units(){
        return $this->hasMany(Unit::class,'user_id');
    }

    public function Levels(){
        return $this->hasMany(Level::class,'user_id');
    }

    public function Achievements(){
        return $this->hasMany(StudentAchievement::class,'user_id');
    }

    public function Subjects(){
        return $this->hasMany(Subject::class,'user_id');
    }

    public function UserSubscription(){
        return $this->hasMany(UserSubscription::class,'user_id');
    }

    public function ContactUS(){
        return $this->hasMany(ContactUs::class,'user_id');
    }

    public function Topics(){
        return $this->hasMany(Topic::class,'user_id');
    }

}

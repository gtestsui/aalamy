<?php

namespace Modules\HelpCenter\Traits\ModelRelations;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\Level;
use Modules\Level\Models\Subject;
use Modules\Notification\Models\FirebaseToken;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\SubscriptionPlan\Models\UserSubscription;
use Modules\User\Http\Controllers\Classes\UserServices;

trait HelpCenterUserGuideRelations
{

    //Relations
    public function Category(){
        return $this->belongsTo('Modules\HelpCenter\Models\HelpCenterCategory','category_id');
    }

    public function Images(){
        return $this->hasMany('Modules\HelpCenter\Models\HelpCenterUserGuideImage','user_guide_id');
    }

    public function Videos(){
        return $this->hasMany('Modules\HelpCenter\Models\HelpCenterUserGuideVideo','user_guide_id');
    }

}

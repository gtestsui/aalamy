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

trait HelpCenterUserGuideImageRelations
{

    //Relations
    public function UserGuide(){
        return $this->belongsTo('Modules\HelpCenter\Models\HelpCenterUserGuide','user_guide_id');
    }

}

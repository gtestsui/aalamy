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
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\Level;
use Modules\Level\Models\Subject;
use Modules\Mark\Models\GradeBook;
use Modules\Meeting\Models\Meeting;
use Modules\Meeting\Models\MeetingTargetUser;
use Modules\Notification\Models\FirebaseToken;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\Quiz;
use Modules\SchoolEmployee\Models\SchoolEmployee;
use Modules\Sticker\Models\Sticker;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\SubscriptionPlan\Models\UserSubscription;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\WorkSchedule\Models\WorkScheduleTeacherHasSubmittedPeriodState;

trait TeacherRelations
{

    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }
    public function UserEvenItsDeleted(){
        return $this->User()->withDeletedItems();
    }

    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    public function ClassInfos(){
        return $this->hasMany('Modules\ClassModule\Models\ClassInfo','teacher_id');
    }

    public function Assignments(){
        return $this->hasMany(Assignment::class,'teacher_id');
    }

    public function ClassStudents(){
        return $this->hasMany(ClassStudent::class,'teacher_id');
    }

    public function Events(){
        return $this->hasMany(Event::class,'teacher_id');
    }

    public function TargetUsers(){
        return $this->hasMany(EventTargetUser::class,'teacher_id');
    }

    public function FeedbackAboutStudents(){
        return $this->hasMany(FeedbackAboutStudent::class,'teacher_id');
    }

    public function QuestionBanks(){
        return $this->hasMany(QuestionBank::class,'teacher_id');
    }

    public function LibraryQuestions(){
        return $this->hasMany(LibraryQuestion::class,'teacher_id');
    }

    public function LearningResources(){
        return $this->hasMany(LearningResource::class,'teacher_id');
    }

    public function Topics(){
        return $this->hasMany(Topic::class,'teacher_id');
    }

    public function GradeBooks(){
        return $this->hasMany(GradeBook::class,'teacher_id');
    }

    public function Meetings(){
        return $this->hasMany(Meeting::class,'teacher_id');
    }

    public function MeetingTargetUsers(){
        return $this->hasMany(MeetingTargetUser::class,'teacher_id');
    }

    public function Quizzes(){
        return $this->hasMany(Quiz::class,'teacher_id');
    }

    public function Stickers(){
        return $this->hasMany(Sticker::class,'teacher_id');
    }

    public function SchoolEmployees(){
        return $this->hasMany(SchoolEmployee::class);
    }

}

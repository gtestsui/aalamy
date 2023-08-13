<?php

namespace Modules\User\Traits\ModelRelations;
use Modules\Assignment\Models\Assignment;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Event\Models\Event;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\Mark\Models\GradeBook;
use Modules\Meeting\Models\Meeting;
use Modules\Outcomes\Models\StudentStudyingInformation;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\Quiz;
use Modules\SchoolInvitation\Models\SchoolTeacherInvitation;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;
use Modules\Sticker\Models\Sticker;
use Modules\User\Models\Teacher;

trait SchoolRelations
{

    //Relations
    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }
    public function UserEvenItsDeleted(){
        return $this->User()->withDeletedItems();
    }

    public function SchoolStudents(){
        return $this->hasMany('Modules\User\Models\SchoolStudent','student_id')
            ->where('is_active',1);
    }

    public function Address(){
        return $this->belongsTo('Modules\Address\Models\Address','address_id');
    }

    public function StudentRequests(){
        return $this->hasMany('Modules\SchoolInvitation\Models\SchoolStudentRequest','school_id');
    }

    public function TeacherInvitations(){
        return $this->hasMany(SchoolTeacherInvitation::class,'school_id');
    }

    public function TeacherRequests(){
        return $this->hasMany(SchoolTeacherRequest::class,'school_id');
    }

    public function Teachers(){
        return $this->hasMany(Teacher::class,'school_id');
    }

    public function Assignments(){
        return $this->hasMany(Assignment::class,'school_id');
    }

    public function ClassInfos(){
        return $this->hasMany(ClassInfo::class,'school_id');
    }

    public function ClassStudents(){
        return $this->hasMany(ClassStudent::class,'school_id');
    }

    public function DiscussionCornerPosts(){
        return $this->hasMany(DiscussionCornerPost::class,'school_id');
    }

    public function DiscussionCornerSurveys(){
        return $this->hasMany(DiscussionCornerSurvey::class,'school_id');
    }

    public function Events(){
        return $this->hasMany(Event::class,'school_id');
    }

    public function FeedbackAboutStudents(){
        return $this->hasMany(FeedbackAboutStudent::class,'school_id');
    }

    public function LibraryQuestions(){
        return $this->hasMany(LibraryQuestion::class,'school_id');
    }

    public function QuestionBanks(){
        return $this->hasMany(QuestionBank::class,'school_id');
    }

    public function LearningResources(){
        return $this->hasMany(LearningResource::class,'school_id');
    }

    public function Topics(){
        return $this->hasMany(Topic::class,'school_id');
    }

    public function GradeBooks(){
        return $this->hasMany(GradeBook::class,'school_id');
    }

    public function Meetings(){
        return $this->hasMany(Meeting::class,'school_id');
    }

    public function Quizzes(){
        return $this->hasMany(Quiz::class,'school_id');
    }

    public function Stickers(){
        return $this->hasMany(Sticker::class,'school_id');
    }

    public function StudentStudyingInformation(){
        return $this->hasMany(StudentStudyingInformation::class,'school_id');
    }

}

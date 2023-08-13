<?php

namespace Modules\User\Traits\ModelRelations;


use Modules\Assignment\Models\Assignment;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Event\Models\Event;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\EducatorStudentRequest\Models\EducatorRosterStudentRequest;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\Mark\Models\GradeBook;
use Modules\Meeting\Models\Meeting;
use Modules\QuestionBank\Models\LibraryQuestion;
use Modules\QuestionBank\Models\QuestionBank;
use Modules\Quiz\Models\Quiz;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;
use Modules\Sticker\Models\Sticker;
use Modules\User\Models\EducatorStudent;

trait EducatorRelations
{

    //Relations
    public function User(){
        return $this->belongsTo('Modules\User\Models\User');
    }
    public function UserEvenItsDeleted(){
        return $this->User()->withDeletedItems();
    }

    public function Assignments(){
        return $this->hasMany(Assignment::class,'educator_id');
    }

    public function ClassInfos(){
        return $this->hasMany(ClassInfo::class,'educator_id');
    }

    public function ClassStudents(){
        return $this->hasMany(ClassStudent::class,'educator_id');
    }

    public function DiscussionCornerPosts(){
        return $this->hasMany(DiscussionCornerPost::class,'educator_id');
    }

    public function DiscussionCornerSurveys(){
        return $this->hasMany(DiscussionCornerSurvey::class,'educator_id');
    }

    public function SchoolRequests(){
        return $this->hasMany(SchoolTeacherRequest::class,'educator_id');
    }

    public function EducatorRosterStudentRequests(){
        return $this->hasMany(EducatorRosterStudentRequest::class,'educator_id');
    }


    public function EducatorStudents(){
        return $this->hasMany(EducatorStudent::class,'educator_id');
    }

    public function Events(){
        return $this->hasMany(Event::class,'educator_id');
    }

    public function FeedbackAboutStudents(){
        return $this->hasMany(FeedbackAboutStudent::class,'educator_id');
    }

    public function QuestionBanks(){
        return $this->hasMany(QuestionBank::class,'educator_id');
    }

    public function LibraryQuestions(){
        return $this->hasMany(LibraryQuestion::class,'educator_id');
    }

    public function LearningResources(){
        return $this->hasMany(LearningResource::class,'educator_id');
    }

    public function Topics(){
        return $this->hasMany(Topic::class,'educator_id');
    }

    public function GradeBooks(){
        return $this->hasMany(GradeBook::class,'educator_id');
    }

    public function Meetings(){
        return $this->hasMany(Meeting::class,'educator_id');
    }

    public function Quizzes(){
        return $this->hasMany(Quiz::class,'educator_id');
    }

    public function Stickers(){
        return $this->hasMany(Sticker::class,'educator_id');
    }

}

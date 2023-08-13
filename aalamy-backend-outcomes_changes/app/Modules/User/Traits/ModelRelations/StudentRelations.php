<?php

namespace Modules\User\Traits\ModelRelations;


use Modules\Event\Models\EventTargetUser;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\EducatorStudentRequest\Models\EducatorRosterStudentRequest;
use Modules\Mark\Models\GradeBookExternalMark;
use Modules\Mark\Models\MongoStudentAnswer;
use Modules\Meeting\Models\MeetingTargetUser;
use Modules\Outcomes\Models\StudentStudyingInformation;
use Modules\Quiz\Models\QuizStudent;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAction;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAttendance;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\Sticker\Models\StudentPageSticker;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\StudentBasicInformation;
use Modules\User\Models\StudentFamilyInformation;
use Modules\User\Models\StudentOtherInformation;
use Modules\User\Models\StudentSocialAndPersonalInformation;

trait StudentRelations
{

    public function User(){
        return $this->belongsTo('Modules\User\Models\User');
    }

    public function ParentStudents(){
        return $this->hasMany('Modules\User\Models\ParentStudent','student_id');
    }

    public function SchoolStudent(){
        return $this->hasOne('Modules\User\Models\SchoolStudent','student_id')
            ->active();
    }

    public function AllSchoolStudent(){
        return $this->hasMany('Modules\User\Models\SchoolStudent','student_id');
    }

//    public function EducatorStudent(){
//        return $this->hasOne('Modules\User\Models\EducatorStudent','student_id')
//            ->active();
//    }

    public function EducatorStudents(){
        return $this->hasMany(EducatorStudent::class,'student_id')
            ->active();
    }

    public function ClassStudents(){
        return $this->hasMany('Modules\ClassModule\Models\ClassStudent','student_id');
    }

    public function EducatorRosterStudentRequests(){
        return $this->hasMany(EducatorRosterStudentRequest::class,'student_id');
    }



    public function TargetUsers(){
        return $this->hasMany(EventTargetUser::class,'student_id');
    }

    public function FeedbackAboutStudents(){
        return $this->hasMany(FeedbackAboutStudent::class,'student_id');
    }

    public function SchoolRequests(){
        return $this->hasMany('Modules\SchoolInvitation\Models\SchoolStudentRequest','student_id');
    }

    public function Achievements(){
        return $this->hasMany(StudentAchievement::class,'student_id');
    }

    public function RosterAssignmentStudentAttendances(){
        return $this->hasMany(RosterAssignmentStudentAttendance::class,'student_id');
    }

    public function RosterAssignmentStudentActions(){
        return $this->hasMany(RosterAssignmentStudentAction::class,'student_id');
    }

    public function RosterAssignmentStudentPages(){
        return $this->hasMany(RosterAssignmentStudentPage::class,'student_id');
    }

    public function StudentPageStickers(){
        return $this->hasMany(StudentPageSticker::class,'student_id');
    }

    public function GradeBookExternalMarks(){
        return $this->hasMany(GradeBookExternalMark::class,'student_id');
    }

    public function MeetingTargetUsers(){
        return $this->hasMany(MeetingTargetUser::class,'student_id');
    }

    public function QuizStudents(){
        return $this->hasMany(QuizStudent::class,'student_id');
    }


    public function BasicInformation(){
        return $this->hasOne(StudentBasicInformation::class);
    }

    public function FamilyInformation(){
        return $this->hasOne(StudentFamilyInformation::class);
    }

    public function OtherInformation(){
        return $this->hasOne(StudentOtherInformation::class);
    }

    public function SocialAndPersonalInformation(){
        return $this->hasOne(StudentSocialAndPersonalInformation::class);
    }

    public function StudentStudyingInformation(){
        return $this->hasMany(StudentStudyingInformation::class,'student_id');
    }


}

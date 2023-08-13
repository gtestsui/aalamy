<?php


namespace Modules\Meeting\Http\Controllers\Classes;


use Modules\Meeting\Http\Controllers\BBB\MeetBBB;
use Modules\Meeting\Models\Meeting;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\MeetingDurationModuleClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\User;

class MeetingServices
{


    public static function joinAsModerator(Meeting $meeting,User $user){
        MeetBBB::setModeratorPassword($meeting->moderator_password);
        $meetingUrl = MeetBBB::joinModerator(
            $meeting->id,
            $user->fname.' '.$user->lname,
            $user->id
        );
        return $meetingUrl;
    }

    public static function joinAsAttende(Meeting $meeting,User $user){
        MeetBBB::setAttendeePassword($meeting->attendee_password);
        $meetingUrl = MeetBBB::joinAttendee(
            $meeting->id,
            $user->fname.' '.$user->lname,
            $user->id
        );
        return $meetingUrl;
    }



    /**
     * @return int
     */
    public static function getAvilableDurationFromMyPlan($user,$teacherId=null){

        $meetingDurationModuleClass = PlanConstraintsManagementFactory::createMeetingDurationModule($user,$teacherId);
        $duration = $meetingDurationModuleClass->getDuration();
        return $duration;
        /*if(isset($teacherId)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $school = School::with('User')->findOrFail($teacher->school_id);
            $meetingDurationModuleClass = MeetingDurationModuleClass::createByOther($school->User,$school);
            $duration = $meetingDurationModuleClass->getDuration();

        }else{
            $meetingDurationModuleClass = MeetingDurationModuleClass::createByOwner($user);
            $duration = $meetingDurationModuleClass->getDuration();
        }
        return $duration;*/
    }


}

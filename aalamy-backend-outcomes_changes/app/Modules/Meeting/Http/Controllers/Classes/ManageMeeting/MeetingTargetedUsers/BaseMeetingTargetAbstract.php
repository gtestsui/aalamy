<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Meeting\Http\Controllers\Classes\RunningMeetingClass;
use Modules\Meeting\Models\Meeting;

abstract class BaseMeetingTargetAbstract
{

    /**
     * the account type of concrete class
     * @var string
     */
    protected $accountType;

    public abstract function getAccountObject();

    public function getAccountType(){
        return $this->accountType;
    }

    /**
     * @param Carbon $date
     * @param string $partOfDateName
     * @return Builder
     */
    protected function getMeetingsTargetMeQuery(){
        $meetingsTargertMeQuery = Meeting::query()
            ->itsTargeteMe($this->getAccountType(),$this->getAccountObject());
        return $meetingsTargertMeQuery;
    }


    /**
     * the running meeting from 2 days ago until now because
     * should not exist running meetings before some hours from now
     * @return Collection
     */
    public function getAllLastCreatedMeetingsTargetMe(){
        $myMeetings = $this->getMeetingsTargetMeQuery()
            ->whereDate('date_time','>=',Carbon::now()->subDays(2))
            ->get();
        return $myMeetings;
    }

    /**
     * @return Collection of Meeting
     */
    public function myRunningMeetingsTargetMe(){
        $myLastCreatedMeetings = $this->getAllLastCreatedMeetingsTargetMe();

        $myRunningMeetings = (new RunningMeetingClass($myLastCreatedMeetings))
            ->getMyRunningMeetings();
        return $myRunningMeetings;
    }

    /**
     * @return Collection of Event model
     *
     */
    public function getAllMeetingsTargetMe(){
        $allMeetingsTargertMe = $this->getMeetingsTargetMeQuery()
            ->get();
        return $allMeetingsTargertMe;
    }

    /**
     *
     * @param $id
     * @return Builder
     *
     */
    protected function getMeetingsTargetMeByIdQuery($id){
        $meetingTargertMe = $this->getMeetingsTargetMeQuery()
            ->where('id',$id);
        return $meetingTargertMe;
    }

    /**
     *
     * @param $id
     * @return Meeting|null
     *
     */
    public function getMeetingsTargetMeById($id){
        $meetingTargertMe = $this->getMeetingsTargetMeByIdQuery($id)
            ->first();
        return $meetingTargertMe;
    }


    /**
     *
     * @param $id
     * @return Meeting
     *
     */
    public function getMeetingsTargetMeByIdOrFail($id){
        $meetingTargertMe = $this->getMeetingsTargetMeByIdQuery($id)
            ->firstOrFail();
        return $meetingTargertMe;
    }






}

<?php


namespace Modules\Meeting\Http\Controllers\Classes;


use Illuminate\Database\Eloquent\Collection;
use Modules\Meeting\Http\Controllers\BBB\MeetBBB;
use Modules\Meeting\Models\Meeting;

class RunningMeetingClass
{

    /**
     * @var array
     */
    private $allRunningMeetings;

    /**
     * @var array
     */
    private $allMeetings;

    /**
     * @var Meeting
     *
     */
    private $lastCreatedMeetings;

    /**
     * @var array
     */
    private $lastCreatedMeetingsIds;

    /**
     * @var array
     */
    private $myMeetingsIdsStillRunning = [];


    /**
     * @param Meeting|Collection $lastCreatedMeetings
     */
    public function __construct($lastCreatedMeetings){
//        $this->allRunningMeetings = MeetBBB::allMeetings();
        $this->allMeetings = MeetBBB::allMeetings();
        $this->lastCreatedMeetings = $lastCreatedMeetings;
    }

//    /**
//     * @return array
//     */
//    private function getAllRunningMeetings(){
//        return $this->allRunningMeetings;
//    }

//    /**
//     * @return array
//     */
//    protected function setAllRunningMeetings(){
//
//        foreach ($this->allMeetings as $meeting){
//            if($meeting['running'] == "true")
//                $this->allRunningMeetings[] = $meeting;
//
//
//            if($this->itsInLastCreatedMeetings($meeting['meetingID']))
//                $this->addToMeetingsIdsStillRuning($runningMeeting['meetingID']);
//        }
//        return $this->allRunningMeetings;
//    }

//    /**
//     * @param Meeting|Collection of Meeting
//     */
//    public function setLastCreatedMeetings($meetings){
//        $this->lastCreatedMeetings = $meetings;
//        return $this;
//    }

    /**
     * @return Meeting|Collection of Meeting
     */
    public function getMyRunningMeetings(){
        return $this->lastCreatedMeetings
            ->whereIn('id',$this->getMyRunningMeetingsIds())
            ->values();
    }

    private function getMyRunningMeetingsIds(){
        $this->prepareLastCreatedMeetingsIds();
        foreach ($this->allMeetings as $meeting){
            if($meeting['running'] == "true" && $this->itsInLastCreatedMeetings($meeting['meetingID']))
                $this->addToMeetingsIdsStillRuning($meeting['meetingID']);
        }
        return $this->myMeetingsIdsStillRunning;
    }

    /**
     * this will return array of key as meeting id and the value as meeting id
     * ex: [1=>1,2=>2] this will improve the compare while with running meeting
     * @return array
     */
    private function prepareLastCreatedMeetingsIds(){
        /*if(!isset($this->lastCreatedMeetings))
            return [];*/

        $this->lastCreatedMeetingsIds = $this->lastCreatedMeetings->pluck('id','id')->toArray();
    }

    /**
     * @return bool
     */
    private function itsInLastCreatedMeetings($runningMeetingId){
        return isset($this->lastCreatedMeetingsIds[$runningMeetingId])
            ?true
            :false;
    }

    private function addToMeetingsIdsStillRuning($runningMeetingId){
        $this->myMeetingsIdsStillRunning[$runningMeetingId] = $runningMeetingId;
    }


}

<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers;


use Modules\User\Models\ParentModel;

class ParentMeetingTarget extends BaseMeetingTargetAbstract
{
    private ParentModel $parent;
    public function __construct(ParentModel $parent)
    {
        $this->parent = $parent;
        $this->accountType = 'parent';
    }

    public function getAccountObject(){
        return $this->parent;
    }


}

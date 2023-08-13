<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventTargetedUsers;



use Modules\User\Models\ParentModel;

class ParentEventTarget extends BaseEventTargetAbstract
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

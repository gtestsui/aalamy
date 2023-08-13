<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner;


use Modules\Event\Http\DTO\EventData;
use Modules\Event\Models\Event;

interface ManageEventOwnerInterface
{

    public function prepareEventTargetUserArray(EventData $eventData,Event $event);

}

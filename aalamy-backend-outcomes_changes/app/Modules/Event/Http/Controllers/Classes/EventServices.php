<?php

namespace Modules\Event\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use Modules\Event\Http\Controllers\Classes\ManageEvent\ManageEventInterface;
use Modules\Event\Models\Event;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class EventServices
{


    public static function checkOwnerEvent(User $user,Event $event,$teacherId=null){
        if(isset($teacherId)){
            if($event->teacher_id != $teacherId)
                throw new ErrorUnAuthorizationException();
        }else{
            if($event->{$user->account_type.'_id'} != $user->{ucfirst($user->account_type)}->id)
                throw new ErrorUnAuthorizationException();
        }
    }

    public static function checkUpdateEvent(User $user,Event $event,$teacherId=null){
        Self::checkOwnerEvent($user,$event,$teacherId);
    }

    public static function checkDeleteEvent(User $user,Event $event,$teacherId=null){
        Self::checkOwnerEvent($user,$event,$teacherId);
    }





}

<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner\EventOwnerManagementFactory;
use Modules\Event\Http\DTO\EventData;
use Modules\Event\Http\Requests\Event\DestroyEventRequest;
use Modules\Event\Http\Requests\Event\GetEventsTargetMeByDateRequest;
use Modules\Event\Http\Requests\Event\UpdateEventRequest;
use Modules\Event\Http\Resources\EventResource;
use Modules\Event\Models\Event;
use Modules\Event\Models\EventTargetUser;
use Modules\Event\Http\Requests\Event\StoreEventRequest;
use Modules\Notification\Jobs\Event\SendNewEventNotification;
use Modules\Notification\Jobs\Event\SendUpdatedEventNotification;
use Modules\User\Http\Controllers\Classes\UserServices;

class EventController extends Controller
{


//    public function getEventsTargetMeByMonth(GetEventsTargetMeByDateRequest $request){
//        $user = $request->user();
//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$request->my_teacher_id);
//        $date =  Carbon::createFromFormat(
//            config('panel.date_format'),$request->date
//        );
//        $events = Event::isTargeteMe($accountType,$accountObject)
//            ->whereMonth('date',$date->month)
//            ->get();
//        return ApiResponseClass::successResponse(EventResource::collection($events));
//
//    }

    /**
     * EventTargetUser we will prepare Arrays to get just
     * the shared between the ids in request and my ids(ether student_ids,parent_ids...)
     */
    public function store(StoreEventRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $eventData = EventData::fromRequest($request,$user);
        $event = Event::create($eventData->allWithoutRelations());

        //add event target users
//        $eventClassByType = EventServices::createManageEventClassByType($user->account_type,$user,$request->my_teacher_id);
        $eventClassByType = EventOwnerManagementFactory::create($user,$request->my_teacher_id);
        $arrayForCreate = $eventClassByType->prepareEventTargetUserArray($eventData,$event);
        EventTargetUser::insert($arrayForCreate);
        DB::commit();
        ServicesClass::dispatchJob(new SendNewEventNotification($arrayForCreate,$user,$event));
        return ApiResponseClass::successResponse(new EventResource($event));

    }

    public function update(UpdateEventRequest $request,$id){
        $event = $request->getEvent();
//        Log::channel('customized_logger')->debug('customized_logger');
        $event->update([
           'name' =>  $request->name,
           'date' =>  ServicesClass::toTimezone($request->date,$request->time_zone,config('panel.timezone')),
        ]);
        ServicesClass::dispatchJob(new SendUpdatedEventNotification($event));

        return ApiResponseClass::successResponse(new EventResource($event));
    }


    public function destroy(DestroyEventRequest $request,$id){
        $event = $request->getEvent();
        $event->delete();
        return ApiResponseClass::deletedResponse();
    }


}

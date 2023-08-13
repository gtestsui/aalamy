<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\RosterAssignment\Http\Resources\RosterAssignmentResource;
use Modules\Event\Http\Controllers\Classes\ManageCalendar\CalendarManagementFactory;
use Modules\Event\Http\Requests\Calendar\GetMyCalendarByDateRequest;
use Modules\Event\Http\Requests\Calendar\GetMyClassCalendarByDateRequest;
use Modules\Event\Http\Resources\EventResource;

class CalendarController extends Controller
{



    /**
     * will return array of events that target me
     * and array of roster_assignments depends on the user type
     */
    public function getMyCalendarByDate(GetMyCalendarByDateRequest $request){
        $user = $request->user();

        $calenderClass = CalendarManagementFactory::create(
            $user,$request->date,$request->filter_date_by,$request->my_teacher_id
        );
        list($rosterAssignments,$eventsTargetMe,$myEvents) =
            $calenderClass->getMyCalendarByPartOfDate();

        return ApiResponseClass::successResponse([
            'events_target_me' => EventResource::collection($eventsTargetMe),
            'my_events' => EventResource::collection($myEvents),
            'roster_assignments' => RosterAssignmentResource::collection($rosterAssignments),
        ]);
    }

    public function getMyClassCalendarByDate(GetMyClassCalendarByDateRequest $request,$class_id){
        $user = $request->user();

        $calenderClass = CalendarManagementFactory::create(
            $user,$request->date,$request->filter_date_by,$request->my_teacher_id
        );

        $rosterAssignments = $calenderClass->getMyClassCalendarByPartOfDate($class_id);

        return ApiResponseClass::successResponse(
            RosterAssignmentResource::collection($rosterAssignments),
        );
    }



}

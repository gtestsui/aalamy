<?php

namespace Modules\Event\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Scopes\WithoutDeletedItemsScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Event\Http\Resources\EventResource;
use Modules\Event\Models\Event;

class Super_EventController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
        DB::enableQueryLog();
//        $event = Event::withoutGlobalScopes()
//            ->leftJoin('schools','schools.id','=','events.school_id')
//            ->leftJoin('teachers','teachers.id','=','events.teacher_id')
//            ->leftJoin('educators','educators.id','=','events.educator_id')
//            ->get();
//        return $event;
//        return(DB::getQueryLog());


        $events = Event::search($request->key,[],[
                'School','Educator.User','Teacher'
            ])
            ->with(['School.User','Teacher.User','Educator.User'])
            ->trashed($soft_delete)
            ->paginate(config('panel.admin_paginate_num'));
//        return(DB::getQueryLog());
        return ApiResponseClass::successResponse(EventResource::collection($events));
    }

    public function softDeleteOrRestore(Request $request,$event_id){
        DB::beginTransaction();
        $event = Event::withDeletedItems()
            ->findOrFail($event_id);
        $event->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new EventResource($event));

    }

    public function destroy(Request $request,$event_id){
        DB::beginTransaction();
        $event = Event::withDeletedItems()
            ->findOrFail($event_id);
        $event->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}

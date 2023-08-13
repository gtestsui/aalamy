<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Notification\Http\Resources\NotificationResource;
use Modules\Notification\Models\Notification;


class NotificationController extends Controller
{


    public function getMyNotificationsCount(Request $request){
        $user = $request->user();

        $notificationsUnSeenCount = Notification::where('user_id',$user->id)
            ->where('is_seen',0)
            ->count();
        return ApiResponseClass::successResponse([
            'count' => $notificationsUnSeenCount
        ]);
    }

    public function getMyNotificationsPaginate(Request $request){
        $user = $request->user();

        $myNotifications = Notification::where('user_id',$user->id)
            ->orderBy('id','desc')
            ->with('Type')
            ->paginate(10);

        return ApiResponseClass::successResponse(
            NotificationResource::collection($myNotifications)
        );
    }

    public function getMyNotifications(Request $request){
        $user = $request->user();
        $myNotifications = Notification::where('user_id',$user->id)
            ->orderBy('id','desc')
            ->with('Type')
            ->paginate(10);

        $notificationsUnSeenCount = Notification::where('user_id',$user->id)
            ->where('is_seen',0)
            ->count();
        return ApiResponseClass::successResponse([
            'my_notification'=>NotificationResource::collection($myNotifications),
            'un_seen_count'=>$notificationsUnSeenCount
        ]);
    }


    public function markAsRead(Request $request,$id){
        $user = $request->user();
//        $validationResult = Validator::make($request->all(),[
//            'notification_id' => 'required|exists:notifications,id',
//        ]);
//        if($validationResult->fails())
//            return ApiResponseClass::validateResponse($validationResult->errors()->first());

        $notification = Notification::where('user_id',$user->id)
            ->findOrFail($id);
        if(!is_null($notification))
            $notification->update([
                'read_date' => Carbon::now()
            ]);
        return ApiResponseClass::successMsgResponse();
    }


    public function markAsSeen(Request $request){
        $user = $request->user();
        $notificationsCount = Notification::where('user_id',$user->id)
//            ->where('is_seen',false)
            ->update(['is_seen'=>1]);
        return ApiResponseClass::successMsgResponse();
    }


}

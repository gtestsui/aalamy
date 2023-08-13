<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use App\Modules\Notification\Http\DTO\ManualNotificationData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Http\Controllers\ManageManualNotification\ManualNotificationFactory;
use Modules\Notification\Http\Resources\ManualNotificationResource;
use Modules\Notification\Jobs\Manual\SendNewManualNotification;
use Modules\Notification\Models\ManualNotification;
use Modules\Notification\Http\Requests\SendManualNotificationRequest;


class ManualNotificationController extends Controller
{

    public function getMySentManualNotification(Request $request){
        $user = $request->user();
        $manageNotificationClass = ManualNotificationFactory::create($user,$request->my_teacher_id);
        $manualNotifications = $manageNotificationClass->getMySentManualNotificationPaginate();

        return ApiResponseClass::successResponse(ManualNotificationResource::collection($manualNotifications));
    }

    public function send(SendManualNotificationRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        //store the notification in data
        $manualNotificationData = ManualNotificationData::fromRequest($request,$user);
        $manualNotification = ManualNotification::create($manualNotificationData->all());

        //store notification receivers in data
        $manageNotificationClass = ManualNotificationFactory::create($user,$request->my_teacher_id);
        $manageNotificationClass->prepareNotificationReceivers($manualNotificationData);
        $manageNotificationClass->insertReceiversToData($manualNotification);

//        ManualNotificationReceiver::insert(
//            $manageNotificationClass->prepareReceiversForCreate($targetUserIds,$manualNotification)
//        );
        DB::commit();
        $manageNotificationClass->dispatchNotification($user,$manualNotification);
//        ServicesClass::dispatchJob(new SendNewManualNotification(
//            $targetUserIds,$user,$manualNotification
//        ));
        return ApiResponseClass::successMsgResponse();
    }



}

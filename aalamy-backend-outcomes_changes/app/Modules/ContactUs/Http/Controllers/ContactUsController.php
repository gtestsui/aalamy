<?php

namespace Modules\ContactUs\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ContactUs\Http\DTO\ContactUsData;
use Modules\ContactUs\Http\Requests\ContactUs\StoreContactUsRequest;
use Modules\ContactUs\Http\Resources\ContactUsResource;
use Modules\ContactUs\Models\ContactUs;
use Modules\Event\Http\Controllers\Classes\EventServices;
use Modules\Event\Http\DTO\EventData;
use Modules\Event\Http\Requests\Event\DestroyEventRequest;
use Modules\Event\Http\Requests\Event\UpdateEventRequest;
use Modules\Event\Http\Resources\EventResource;
use Modules\Event\Models\Event;
use Modules\Event\Models\EventTargetUser;
use Modules\Event\Http\Requests\Event\StoreEventRequest;
use Modules\Notification\Jobs\ContactUs\SendNewContactUsNotification;
use Modules\Notification\Jobs\Event\SendNewEventNotification;

class ContactUsController extends Controller
{

    /**
     * EventTargetUser we will prepare Arrays to get just
     * the shared between the ids in request and my ids(ether student_ids,parent_ids...)
     */
    public function store(StoreContactUsRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $contactUsData = ContactUsData::fromRequest($request);
        $contactUs = ContactUs::create($contactUsData->all());

//        DB::commit();
        ServicesClass::dispatchJob(new SendNewContactUsNotification($contactUs,$user));
        return ApiResponseClass::successResponse(new ContactUsResource($contactUs));

    }



}

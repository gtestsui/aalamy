<?php

namespace Modules\ContactUs\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use Modules\User\Models\User;

class Super_ContactUsController extends Controller
{

    public function paginate(Request $request){
        $contactUs = ContactUs::search($request->key,[],[
                'User'
            ])
            ->with(['User'=>function($query){
                return $query->with(['Student','School','Educator','Parent']);
            }])
            ->paginate(10);
        return ApiResponseClass::successResponse(ContactUsResource::collection($contactUs));

    }

    public function destroy(Request $request,$id){
        $contactUs = ContactUs::findOrFail($id);
        $contactUs->delete();
        return ApiResponseClass::deletedResponse();
    }



}

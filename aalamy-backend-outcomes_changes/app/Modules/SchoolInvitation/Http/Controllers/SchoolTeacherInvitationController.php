<?php

namespace Modules\SchoolInvitation\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Jobs\SchoolRequest\SendSchoolTeacherInvitationNotification;
use Modules\SchoolInvitation\Http\Requests\SchoolTeacher\SendSchoolTeacherInvitationRequest;
use Modules\SchoolInvitation\Models\SchoolTeacherInvitation;
use Modules\User\Http\Controllers\Classes\UserServices;


class SchoolTeacherInvitationController extends Controller
{


    public function send(SendSchoolTeacherInvitationRequest $request){
        $user = $request->user();
        list(,$school) = UserServices::getAccountTypeAndObject($user);
//        $user->load('School');

        $link = config('SchoolInvitation.panel.teacher_invitation_link').
            $school->id;
        DB::beginTransaction();
        $schoolTeacherInvitation = SchoolTeacherInvitation::create([
            'school_id' => $school->id,
//            'type' => $user->School->id,
            'teacher_email' => $request->teacher_email,
            'link' => $link,
        ]);
        dispatchJob(new SendSchoolTeacherInvitationNotification($user,$schoolTeacherInvitation,$request->introductory_message));
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }


}

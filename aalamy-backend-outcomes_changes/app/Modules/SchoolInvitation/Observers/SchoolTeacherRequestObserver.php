<?php

namespace Modules\SchoolInvitation\Observers;

use Modules\SchoolInvitation\Models\SchoolTeacherRequest;
use Modules\User\Models\Teacher;

class SchoolTeacherRequestObserver
{

    public function creating(SchoolTeacherRequest $schoolTeacherRequest)
    {


    }

    public function created(SchoolTeacherRequest $schoolTeacherRequest)
    {


    }

    public function updated(SchoolTeacherRequest $schoolTeacherRequest)
    {
        if($schoolTeacherRequest->wasChanged('status') && $schoolTeacherRequest->status == 'approved'){
            $schoolTeacherRequest->load('Educator');
            Teacher::create([
                'user_id' => $schoolTeacherRequest->Educator->user_id,
                'school_id' => $schoolTeacherRequest->school_id,
                'bio' => $schoolTeacherRequest->Educator->bio,
            ]);
        }

    }

    public function deleted(SchoolTeacherRequest $schoolTeacherRequest)
    {
        //
    }

    public function restored(SchoolTeacherRequest $schoolTeacherRequest)
    {
        //
    }

    public function forceDeleted(SchoolTeacherRequest $schoolTeacherRequest)
    {
        //
    }
}

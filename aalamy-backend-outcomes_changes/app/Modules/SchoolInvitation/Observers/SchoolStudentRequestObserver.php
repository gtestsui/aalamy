<?php

namespace Modules\SchoolInvitation\Observers;

use Carbon\Carbon;
use Modules\SchoolInvitation\Models\SchoolStudentRequest;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;

class SchoolStudentRequestObserver
{

    public function creating(SchoolStudentRequest $schoolStudentRequest)
    {

    }

    public function created(SchoolStudentRequest $schoolStudentRequest)
    {


    }

    public function updated(SchoolStudentRequest $schoolStudentRequest)
    {
        if($schoolStudentRequest->wasChanged('status') && $schoolStudentRequest->status == 'approved'){
//            SchoolStudent::create([
//                'student_id' => $schoolStudentRequest->student_id,
//                'school_id' => $schoolStudentRequest->school_id,
//                'start_date' => Carbon::now(),
//            ]);
        }

    }

    public function deleted(SchoolStudentRequest $schoolStudentRequest)
    {
        //
    }

    public function restored(SchoolStudentRequest $schoolStudentRequest)
    {
        //
    }

    public function forceDeleted(SchoolStudentRequest $schoolStudentRequest)
    {
        //
    }
}

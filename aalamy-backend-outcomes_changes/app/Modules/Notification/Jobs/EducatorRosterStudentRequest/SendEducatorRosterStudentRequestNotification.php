<?php

namespace Modules\Notification\Jobs\EducatorRosterStudentRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Http\Controllers\Classes\EducatorRosterStudentRequest\EducatorRosterStudentRequestNotification;
use Modules\Roster\Models\Roster;
use Modules\User\Models\User;

class SendEducatorRosterStudentRequestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var User $fromUser
     * @var array $studentIds
     * @var Roster $roster
     * @var String $introductoryMessage
     * @return void
     */
    private $studentIds,$fromUser,$roster,$introductoryMessage;
    public function __construct(array $studentIds,User $fromUser,Roster $roster,$introductoryMessage=null)
    {
        $this->studentIds   = $studentIds;
        $this->fromUser     = $fromUser;
        $this->roster       = $roster;
        $this->introductoryMessage = $introductoryMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $educatorRosterStudentRequestNotification = new EducatorRosterStudentRequestNotification($this->studentIds,$this->fromUser ,  $this->roster ,$this->introductoryMessage
        );
        $educatorRosterStudentRequestNotification->notifyFor();
        $educatorRosterStudentRequestNotification->notifyToFirebase();
        $educatorRosterStudentRequestNotification->notifyToDataBase();
//        $educatorRosterStudentRequestNotification->notifyToMail();
    }
}

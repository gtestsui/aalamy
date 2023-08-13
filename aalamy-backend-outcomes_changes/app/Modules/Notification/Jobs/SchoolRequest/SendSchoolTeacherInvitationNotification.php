<?php

namespace Modules\Notification\Jobs\SchoolRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\SchoolTeacherInvitationNotification;
use Modules\Notification\Http\Controllers\Classes\Notifications\VerificationCodeNotification;

class SendSchoolTeacherInvitationNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $user,$schoolTeacherInvitation,$introductoryMessage;
    public function __construct($user,$schoolTeacherInvitation,$introductoryMessage)
    {
        $this->user = $user;
        $this->schoolTeacherInvitation = $schoolTeacherInvitation;
        $this->introductoryMessage = $introductoryMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $schoolTeacherInvitationNotification = new SchoolTeacherInvitationNotification($this->user,$this->schoolTeacherInvitation,$this->introductoryMessage);
//        $toUserIds = $verificationCodeNotification->notifyFor();
//        $verificationCodeNotification->notifyToFirebase($toUserIds);
//        $verificationCodeNotification->notifyToDataBase($toUserIds);
        $schoolTeacherInvitationNotification->notifyToMail();
    }
}

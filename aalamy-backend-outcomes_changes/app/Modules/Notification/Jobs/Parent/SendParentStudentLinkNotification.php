<?php

namespace Modules\Notification\Jobs\Parent;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Http\Controllers\Classes\Parent\ParentStudentLinkNotification;

class SendParentStudentLinkNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $user,$student,$toEmail;
    public function __construct($user,$student,$toEmail)
    {
        $this->user = $user;
        $this->student = $student;
        $this->toEmail = $toEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parentStudentLinkNotification = new ParentStudentLinkNotification($this->user,$this->student,$this->toEmail);
//        $toUserIds = $verificationCodeNotification->notifyFor();
//        $verificationCodeNotification->notifyToFirebase($toUserIds);
//        $verificationCodeNotification->notifyToDataBase($toUserIds);
        $parentStudentLinkNotification->notifyToMail();
    }
}

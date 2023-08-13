<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Http\Controllers\Classes\Notifications\VerificationCodeNotification;

class SendVerificationCodeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $user,$code;
    public function __construct($user,$code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $verificationCodeNotification = new VerificationCodeNotification($this->user,$this->code);
        $toUserIds = $verificationCodeNotification->notifyFor();
//        $verificationCodeNotification->notifyToFirebase($toUserIds);
//        $verificationCodeNotification->notifyToDataBase($toUserIds);
        $verificationCodeNotification->notifyToMail();
    }
}

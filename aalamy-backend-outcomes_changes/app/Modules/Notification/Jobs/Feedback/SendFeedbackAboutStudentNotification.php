<?php

namespace Modules\Notification\Jobs\Feedback;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Notification\Http\Controllers\Classes\Feedback\FeedbackAboutStudentNotification;
use Modules\Notification\Http\Controllers\Classes\Parent\ParentStudentLinkNotification;

class SendFeedbackAboutStudentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $feedbackAboutStudent;
    public function __construct(FeedbackAboutStudent $feedbackAboutStudent)
    {
        $this->feedbackAboutStudent = $feedbackAboutStudent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $feedbackAboutStudentNotification = new FeedbackAboutStudentNotification($this->feedbackAboutStudent);
        $toUserIds = $feedbackAboutStudentNotification->notifyFor();
        $feedbackAboutStudentNotification->notifyToFirebase();
        $feedbackAboutStudentNotification->notifyToDataBase();
//        $feedbackAboutStudentNotification->notifyToMail();
    }
}

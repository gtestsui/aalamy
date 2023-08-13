<?php

namespace Modules\Notification\Jobs\Achievement;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Event;
use Modules\Notification\Http\Controllers\Classes\Achievement\NewAchievementNotification;
use Modules\Notification\Http\Controllers\Classes\Achievement\NewAchievementWaitingPublishNotification;
use Modules\Notification\Http\Controllers\Classes\EducatorRosterStudentRequest\EducatorRosterStudentRequestNotification;
use Modules\Notification\Http\Controllers\Classes\Event\NewEventNotification;
use Modules\Roster\Models\Roster;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Models\User;

class SendNewAchievementWaitingPublishNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var StudentAchievement $achievement
     * @return void
     */
    private StudentAchievement $achievement;
    public function __construct(StudentAchievement $achievement)
    {

        $this->achievement   = $achievement;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newAchievementWaitingPublishNotification =
            new NewAchievementWaitingPublishNotification(
                $this->achievement
            );
        $newAchievementWaitingPublishNotification->notifyFor();
        $newAchievementWaitingPublishNotification->notifyToFirebase();
        $newAchievementWaitingPublishNotification->notifyToDataBase();
//        $newEventNotification->notifyToMail();
    }
}

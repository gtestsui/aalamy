<?php

namespace Modules\Notification\Jobs\Achievement;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Event;
use Modules\Notification\Http\Controllers\Classes\Achievement\NewAchievementNotification;
use Modules\Notification\Http\Controllers\Classes\EducatorRosterStudentRequest\EducatorRosterStudentRequestNotification;
use Modules\Notification\Http\Controllers\Classes\Event\NewEventNotification;
use Modules\Roster\Models\Roster;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Models\User;

/**
 * @note this will send notification to the student in same classes with the student who has
 * win the achievement
 */
class SendNewAchievementNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var array $targetUserIdsArray
     * @var StudentAchievement $achievement
     * @return void
     */
    private $classIds,$achievement;
    public function __construct(array $classIds,StudentAchievement $achievement)
    {
        $this->classIds = $classIds;
        $this->achievement        = $achievement;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newAchievementNotification = new NewAchievementNotification($this->classIds,$this->achievement);
        $newAchievementNotification->notifyFor();
        $newAchievementNotification->notifyToFirebase();
        $newAchievementNotification->notifyToDataBase();
//        $newEventNotification->notifyToMail();
    }
}

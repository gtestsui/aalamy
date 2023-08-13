<?php

namespace App\Console;

use App\Console\Commands\DatabaseBackup;
use App\Modules\RosterAssignment\Console\Commands\LockRosterAssignment;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Setting\Console\Commands\ForceDeleteSoftDeletedItems;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        LockRosterAssignment::class,
        ForceDeleteSoftDeletedItems::class,
        DatabaseBackup::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $forceDeleteSoftDeletedItemsCommand = new ForceDeleteSoftDeletedItems();
        $lockRosterAssignmentCommand = new LockRosterAssignment();
        $databaseBackupCommand = new DatabaseBackup();
        // $schedule->command('inspire')->hourly();
         $schedule->command($lockRosterAssignmentCommand->getCommand())->everyMinute();
         $schedule->command($forceDeleteSoftDeletedItemsCommand->getCommand())->daily();
         $schedule->command($databaseBackupCommand->getCommand())->hourly();

//        $schedule->call(function () {
//            DB::table('logged_devices')->delete();
//            DB::table('logged_devices')->insert([
//                'user_id' => 15,
//                'device_type' => 'mobile',
//                'device_mac' => '1233',
//                'created_at' => Carbon::now(),
//            ]);
//        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

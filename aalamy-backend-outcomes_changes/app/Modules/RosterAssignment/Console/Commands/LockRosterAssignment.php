<?php

namespace App\Modules\RosterAssignment\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\RosterAssignment\Models\RosterAssignment;

class LockRosterAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roster-assignment:lock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lock roster assignment objects after expiration date done';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filterRosterAssignmentData = FilterRosterAssignmentData::fromArray([
            'end_date' => Carbon::now()->format('Y/m/d')

        ]);
        RosterAssignment::isLocked(false)
            ->filter($filterRosterAssignmentData)
            ->update([
                'is_locked' => true
            ]);

        $this->info('expire roster assignments locked successfully');
        return 0;
    }

    public function getCommand(){
        return $this->signature;
    }

}

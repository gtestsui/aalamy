<?php

namespace Modules\Setting\Console\Commands;

use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\ServicesClass;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Modules\Event\Models\Event;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Setting\Models\DeleteDataSetting;
use Modules\Setting\Models\Setting;

class ForceDeleteSoftDeletedItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soft-deleted-items:force-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft deleted items in database has been force deleted successfully';

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
        DB::beginTransaction();
        $setting = DeleteDataSetting::first();
        $typeOfTime = ucfirst($setting->type);
        $subMethod = 'sub'.$typeOfTime;
        $dateShouldDeleteAllItemsBeforeIt = Carbon::now()->{$subMethod}($setting->time_for_force_delete_data);
        $allModelPaths = ServicesClass::getAllModelsPaths();
//        $this->info($dateShouldDeleteAllItemsBeforeIt);
        foreach ($allModelPaths as $modelPath){

            try{
                $modelPath::trashed(true)->whereDate('deleted_at','<=',$dateShouldDeleteAllItemsBeforeIt)->delete();

            }catch (\BadMethodCallException/*|QueryException*/ $e){

            }
        }

        $this->info('deleted successfully');
        DB::commit();
        return 0;
    }

    public function getCommand(){
        return $this->signature;
    }


}

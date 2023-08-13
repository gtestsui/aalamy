<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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


        $filename = "backup-" . Carbon::now()->format('H') . ".sql";

//        $t = '/tmp/' .env('DB_DATABASE') . '_' . date("Y-m-d_Hi") . '.sql';
//        $t = 'C:\wamp\bin\mysql\mysql5.7.26\bin\mysqldump.exe';
//        $t = 'C:\xampp\mysql\bin\mysql.exe';
        $mysqlDump = 'C:\xampp\mysql\bin\mysqldump.exe';
        $targetPath = storage_path()."/app/backup/";
        if(!File::exists($targetPath)){
            File::makeDirectory($targetPath);
        }

        $command = "".$mysqlDump." --user="
            . env('DB_USERNAME','root')
            . " --password=" . env('DB_PASSWORD')
            . " --host=" . env('DB_HOST','127.0.0.1') . " " . env('DB_DATABASE','class_kits')
            . "  > " . $targetPath . $filename;

        $this->info($command);
        $returnVar = NULL;
        $output = NULL;


        $this->info(exec($command, $output, $returnVar));

        $this->info('successfully');

        return 0;
    }


    public function getCommand(){
        return $this->signature;
    }
}

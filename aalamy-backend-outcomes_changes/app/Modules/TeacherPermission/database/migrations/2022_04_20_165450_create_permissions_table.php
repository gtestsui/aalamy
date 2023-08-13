<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();


            $table->string('name');
            $table->tinyInteger('num');

            $table->boolean('deleted')->default(0);
            $table->boolean('deleted_by_cascade')->default(0);
            $table->dateTime('deleted_at')->nullable();

            $table->timestamps();
        });

        foreach (configFromModule('panel.permissions',\App\Http\Controllers\Classes\ApplicationModules::TEACHER_PERMISSION_MODULE_NAME) as $permission){

            $num = configFromModule('panel.permissions_num.'.$permission,\App\Http\Controllers\Classes\ApplicationModules::TEACHER_PERMISSION_MODULE_NAME);
            DB::table('permissions')->insert([
               'name' =>  $permission,
               'num' =>  $num,
            ]);
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}

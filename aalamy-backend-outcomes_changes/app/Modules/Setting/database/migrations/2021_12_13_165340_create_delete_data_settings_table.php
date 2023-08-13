<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateDeleteDataSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delete_data_settings', function (Blueprint $table) {
            $table->id();

            $table->integer('time_for_force_delete_data');
            $table->enum('type',config('Setting.panel.delete_in_types'));

            $table->timestamps();
        });


        DB::table('delete_data_settings')->insert([
           'time_for_force_delete_data' => 24,
           'type' => config('Setting.panel.delete_in_types.months'),
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delete_data_settings');
    }
}

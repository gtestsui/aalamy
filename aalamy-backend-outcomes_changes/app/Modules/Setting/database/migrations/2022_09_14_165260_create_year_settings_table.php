<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateYearSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_settings', function (Blueprint $table) {
            $table->id();

            $table->date('start_date');
            $table->date('end_date');

            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('year_settings')->insert([
           'start_date' => \Carbon\Carbon::now(),
           'end_date' => \Carbon\Carbon::now()->addYear(),
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('year_settings');
    }
}

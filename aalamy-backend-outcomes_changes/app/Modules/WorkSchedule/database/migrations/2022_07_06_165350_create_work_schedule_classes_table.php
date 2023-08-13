<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateWorkScheduleClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_schedule_classes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('week_day_id');
            $table->foreign('week_day_id')->references('id')->on('week_days')->onDelete('cascade');

            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');

            $table->unsignedBigInteger('class_info_id')->nullable();
            $table->foreign('class_info_id')->references('id')->on('class_infos')->onDelete('cascade');

            $table->integer('period_number');

            $table->time('start');
            $table->time('end');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_schedule_classes');
    }
}

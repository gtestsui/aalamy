<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateBaseLevelSubjectRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_level_subject_rules', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('base_level_subject_id');
            $table->foreign('base_level_subject_id')->references('id')->on('base_level_subjects')->onDelete('cascade');


            $table->boolean('requires_failure');
            $table->boolean('enter_the_overall_total');
            $table->boolean('optional');
            $table->smallInteger('max_degree');
            $table->smallInteger('min_degree');
            $table->smallInteger('failure_point');
            $table->boolean('its_one_field');
            $table->smallInteger('classes_count_at_week');

            $table->boolean('deleted')->default(0);
            $table->boolean('deleted_by_cascade')->default(0);
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('base_level_subject_rules');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateBaseLevelSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_level_subjects', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('base_level_id');
            $table->foreign('base_level_id')->references('id')->on('base_levels')->onDelete('cascade');

            $table->unsignedBigInteger('base_subject_id');
            $table->foreign('base_subject_id')->references('id')->on('base_subjects')->onDelete('cascade');

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
        Schema::dropIfExists('base_level_subjects');
    }
}

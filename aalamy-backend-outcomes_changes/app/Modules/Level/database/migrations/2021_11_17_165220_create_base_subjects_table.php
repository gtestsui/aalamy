<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateBaseSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_subjects', function (Blueprint $table) {
            $table->id();


            $table->string('name');
            $table->string('code');
            $table->tinyInteger('semester');//1 or 2
            $table->boolean('hyperlink');
            $table->unsignedBigInteger('base_subject_id')->nullable();//when hyperlink its true then its belong to another subject and this won't be null
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
        Schema::dropIfExists('base_subjects');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentFamilyInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_family_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->string('father_work')->nullable();
            $table->string('father_phone')->nullable();
            $table->boolean('mother_living_with_father')->nullable();
            $table->string('mother_work')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('father_studying')->nullable();
            $table->string('mother_studying')->nullable();
            $table->integer('family_income')->nullable();
            $table->boolean('father_and_mother_are_relatives')->nullable();
            $table->integer('older_brothers_count')->nullable();
            $table->integer('younger_brothers_count')->nullable();
            $table->integer('older_sisters_count')->nullable();
            $table->integer('younger_sisters_count')->nullable();
            $table->boolean('have_uncle_from_father')->nullable();
            $table->boolean('have_uncle_from_mother')->nullable();
            $table->boolean('living_in_same_house')->nullable();
            $table->boolean('have_internet_in_the_house')->nullable();
            $table->integer('workers_from_the_family_count')->nullable();

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
        Schema::dropIfExists('student_family_information');
    }
}

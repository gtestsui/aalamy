<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateGradeBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_books', function (Blueprint $table) {
            $table->id();

            //who create the sticker
            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedBigInteger('educator_id')->nullable();
            $table->foreign('educator_id')->references('id')->on('educators')->onDelete('cascade');

            $table->unsignedBigInteger('roster_id');
            $table->foreign('roster_id')->references('id')->on('rosters')->onDelete('cascade');


            $table->unsignedBigInteger('level_subject_id');
            $table->foreign('level_subject_id')->references('id')->on('level_subjects')->onDelete('cascade');

//            $table->unsignedBigInteger('unit_id')->nullable();
//            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
//
//            $table->unsignedBigInteger('lesson_id')->nullable();
//            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');


            $table->string('grade_book_name');
            $table->integer('external_marks_weight')->default(0);
            $table->string('file')->nullable();


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
        Schema::dropIfExists('grade_books');
    }
}

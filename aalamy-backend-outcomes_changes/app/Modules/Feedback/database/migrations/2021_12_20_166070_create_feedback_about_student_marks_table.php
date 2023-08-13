<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateFeedbackAboutStudentMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_about_student_marks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('feedback_id');
            $table->foreign('feedback_id')->references('id')->on('feedback_about_students')->onDelete('cascade');

            $table->string('mark_file');
//            $table->unsignedBigInteger('assignment_id');
//            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');

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
        Schema::dropIfExists('feedback_about_student_marks');
    }
}

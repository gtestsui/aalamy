<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateQuizQuestionStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_question_student_answers', function (Blueprint $table) {
            $table->id();

            //who create the sticker
            $table->unsignedBigInteger('quiz_question_id');
            $table->foreign('quiz_question_id')->references('id')->on('quiz_questions')->onDelete('cascade');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->unsignedBigInteger('quiz_student_id');
            $table->foreign('quiz_student_id')->references('id')->on('quiz_students')->onDelete('cascade');


            $table->float('mark',0,0)->default(0);
            $table->boolean('answer_status')->default(false);

            $table->boolean('deleted')->default(0);
            $table->boolean('deleted_by_cascade')->default(0);
            $table->dateTime('deleted_at')->nullable();


            //            $table->timestamp('created_at')->useCurrent();
//            $table->timestamp('updated_at')->useCurrent();

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
        Schema::dropIfExists('question_student_answers');
    }
}

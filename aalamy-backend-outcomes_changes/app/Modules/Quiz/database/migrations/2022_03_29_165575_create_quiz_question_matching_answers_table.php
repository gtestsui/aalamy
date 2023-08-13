<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateQuizQuestionMatchingAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_question_matching_answers', function (Blueprint $table) {
            $table->id();

            //who create the sticker
            $table->unsignedBigInteger('quiz_question_student_answer_id');
            $table->foreign('quiz_question_student_answer_id','q_q_s_match_id_foreign')->references('id')->on('quiz_question_student_answers')->onDelete('cascade');

            $table->unsignedBigInteger('left_list_id');
            $table->foreign('left_list_id')->references('id')->on('question_bank_matching_left_lists')->onDelete('cascade');

            $table->unsignedBigInteger('right_list_id');
            $table->foreign('right_list_id')->references('id')->on('question_bank_matching_right_lists')->onDelete('cascade');

            $table->boolean('answer_status');


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
        Schema::dropIfExists('matching_answers');
    }
}

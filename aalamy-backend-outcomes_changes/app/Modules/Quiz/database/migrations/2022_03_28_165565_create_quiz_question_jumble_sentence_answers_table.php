<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateQuizQuestionJumbleSentenceAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_question_jumble_sentence_answers', function (Blueprint $table) {
            $table->id();

            //who create the sticker
            $table->unsignedBigInteger('quiz_question_student_answer_id');
            $table->foreign('quiz_question_student_answer_id','q_q_s_jumble_id_foreign')->references('id')->on('quiz_question_student_answers')->onDelete('cascade');

            $table->unsignedBigInteger('jumble_sentence_id');
            $table->foreign('jumble_sentence_id')->references('id')->on('question_bank_jumble_sentences')->onDelete('cascade');
            $table->integer('order');

//            $table->string('word');
//            $table->json('inserted_words');

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
        Schema::dropIfExists('jumble_sentence_answers');
    }
}

<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateTrueFalseQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('true_false_questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('flash_card_id');
            $table->foreign('flash_card_id')->references('id')->on('flash_cards')->onDelete('cascade');

            $table->unsignedBigInteger('question_card_id');
            $table->foreign('question_card_id')->references('id')->on('cards')->onDelete('cascade');

            $table->unsignedBigInteger('answer_card_id');
            $table->foreign('answer_card_id')->references('id')->on('cards')->onDelete('cascade');

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
        Schema::dropIfExists('true_false_questions');
    }
}

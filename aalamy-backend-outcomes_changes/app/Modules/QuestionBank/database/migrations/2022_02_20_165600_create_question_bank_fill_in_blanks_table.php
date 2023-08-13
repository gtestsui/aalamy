<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateQuestionBankFillInBlanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_bank_fill_in_blanks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id')->references('id')->on('question_banks')->onDelete('cascade');

            $table->string('word');
            $table->tinyInteger('order');

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
        Schema::dropIfExists('question_bank_fill_in_blanks');
    }
}

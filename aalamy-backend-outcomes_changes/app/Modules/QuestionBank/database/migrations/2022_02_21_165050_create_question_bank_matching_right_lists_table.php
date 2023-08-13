<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateQuestionBankMatchingRightListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_bank_matching_right_lists', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id')->references('id')->on('question_banks')->onDelete('cascade');
            //the correct choice while matching with left list
            $table->unsignedBigInteger('left_list_id')->nullable();
            $table->foreign('left_list_id')->references('id')->on('question_bank_matching_left_lists')->onDelete('cascade');

            $table->mediumText('text');

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
        Schema::dropIfExists('question_bank_matching_right_lists');
    }
}

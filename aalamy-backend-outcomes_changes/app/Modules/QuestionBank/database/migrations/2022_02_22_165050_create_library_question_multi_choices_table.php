<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateLibraryQuestionMultiChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_question_multi_choices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('library_question_id')->nullable();
            $table->foreign('library_question_id')->references('id')->on('library_questions')->onDelete('cascade');

            $table->string('choice');
            $table->boolean('status');

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
        Schema::dropIfExists('library_question_multi_choices');
    }
}

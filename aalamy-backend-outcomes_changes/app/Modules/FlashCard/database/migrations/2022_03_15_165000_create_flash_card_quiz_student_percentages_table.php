<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateFlashCardQuizStudentPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_card_quiz_student_percentages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('flash_card_id')->nullable();
            $table->foreign('flash_card_id')->references('id')->on('flash_cards')->onDelete('cascade');

//            $table->unsignedBigInteger('flash_card_id');
//            $table->foreign('flash_card_id')->references('id')->on('flash_cards')->onDelete('cascade');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->integer('percentage');

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
        Schema::dropIfExists('flash_card_quiz_student_percentages');
    }
}

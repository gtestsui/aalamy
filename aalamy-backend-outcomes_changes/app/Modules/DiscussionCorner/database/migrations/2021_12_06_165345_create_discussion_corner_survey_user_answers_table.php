<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateDiscussionCornerSurveyUserAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_corner_survey_user_answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('survey_user_id');
            $table->foreign('survey_user_id')->references('id')->on('discussion_corner_survey_users')->onDelete('cascade');

            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('discussion_corner_survey_questions')->onDelete('cascade');

            $table->unsignedBigInteger('choice_id')->nullable();
            $table->foreign('choice_id')->references('id')->on('discussion_corner_survey_question_choices')->onDelete('cascade');

            $table->mediumText('written_answer')->nullable();

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
        Schema::dropIfExists('discussion_corner_survey_user_answers');
    }
}

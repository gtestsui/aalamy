<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateDiscussionCornerSurveyQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_corner_survey_questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('survey_id');
            $table->foreign('survey_id')->references('id')->on('discussion_corner_surveys')->onDelete('cascade');


            $table->mediumText('question');
            $table->enum('question_type',config('DiscussionCorner.panel.survey_question_types'));
            $table->boolean('is_required')->default(1);

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
        Schema::dropIfExists('discussion_corner_survey_questions');
    }
}

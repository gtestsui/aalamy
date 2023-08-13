<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Support\Facades\Schema;

class CreateLibraryQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_questions', function (Blueprint $table) {
            $table->id();

            $table->text('question');
            $table->enum('question_type',configFromModule('panel.question_types',ApplicationModules::QUESTION_BANK_MODULE_NAME));

            $table->tinyInteger('difficult_level');
            $table->enum('share_type',configFromModule('panel.question_share_types_with_library',ApplicationModules::QUESTION_BANK_MODULE_NAME));

            //we have deleted the cascade because we don't want to delete items
            //from this section when the user has been deleted
            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->unsignedBigInteger('educator_id')->nullable();
            $table->foreign('educator_id')->references('id')->on('educators');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers');

            //we have deleted the cascade because we don't want to delete items
            //from this section when the related items have been deleted
            $table->unsignedBigInteger('level_subject_id');
            $table->foreign('level_subject_id')->references('id')->on('level_subjects');

            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units');

            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->foreign('lesson_id')->references('id')->on('lessons');



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
        Schema::dropIfExists('library_questions');
    }
}

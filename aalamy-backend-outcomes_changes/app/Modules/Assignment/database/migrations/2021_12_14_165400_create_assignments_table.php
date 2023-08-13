<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('assignment_folder_id')->nullable();
            $table->foreign('assignment_folder_id')->references('id')->on('assignment_folders')->onDelete('cascade');


            //who create the assignment
            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedBigInteger('educator_id')->nullable();
            $table->foreign('educator_id')->references('id')->on('educators')->onDelete('cascade');


            $table->unsignedBigInteger('level_subject_id');
            $table->foreign('level_subject_id')->references('id')->on('level_subjects')->onDelete('cascade');

            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');

            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');


            $table->string('name');
            $table->text('description');
            $table->boolean('is_locked')->default(config('Assignment.panel.assignment.is_locked_default'));
            $table->boolean('is_hidden')->default(config('Assignment.panel.assignment.is_hidden_default'));
            $table->boolean('prevent_request_help')->default(config('Assignment.panel.assignment.prevent_request_help_default'));
            $table->boolean('display_mark')->default(config('Assignment.panel.assignment.display_mark_default'));
            $table->boolean('is_auto_saved')->default(config('Assignment.panel.assignment.is_auto_saved_default'));
            $table->boolean('prevent_moved_between_pages')->default(config('Assignment.panel.assignment.prevent_moved_between_pages_default'));
            $table->boolean('is_shuffling')->default(config('Assignment.panel.assignment.is_shuffling_default'));
            $table->dateTime('timer')->nullable();


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
        Schema::dropIfExists('assignments');
    }
}

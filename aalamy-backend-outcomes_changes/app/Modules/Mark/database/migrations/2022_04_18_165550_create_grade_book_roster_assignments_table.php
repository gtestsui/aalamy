<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateGradeBookRosterAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_book_roster_assignments', function (Blueprint $table) {
            $table->id();

            //who create the sticker
            $table->unsignedBigInteger('grade_book_id');
            $table->foreign('grade_book_id','grade_ros_ass_id')->references('id')->on('grade_books')->onDelete('cascade');

            $table->unsignedBigInteger('roster_assignment_id');
            $table->foreign('roster_assignment_id','ros_ass_grade_id')->references('id')->on('roster_assignments')->onDelete('cascade');


            $table->integer('weight');


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
        Schema::dropIfExists('grade_book_roster_assignments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateRosterAssignmentStudentsAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster_assignment_students_attendance', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('roster_assignment_id')->nullable();
            $table->foreign('roster_assignment_id','ro_as_att_id_foreign')->references('id')->on('roster_assignments')->onDelete('cascade');

            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');


            $table->boolean('attendee_status')->default(false);
            $table->text('note')->nullable();


            $table->boolean('deleted')->default(0);
            $table->boolean('deleted_by_cascade')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

//            $table->unique(['roster_assignment_id', 'student_id'],'ro_ass_stu_id');

        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roster_assignment_students_attendance');
    }
}

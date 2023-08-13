<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateRosterAssignmentStudentActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {//we have stored all actions here to reduce query num
        Schema::create('roster_assignment_student_actions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('roster_assignment_id')->nullable();
            $table->foreign('roster_assignment_id')->references('id')->on('roster_assignments')->onDelete('cascade');

            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');


            $table->boolean('help_request')->default(false);
            $table->boolean('check_answer_request')->default(false);


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
        Schema::dropIfExists('roster_assignment_student_actions');
    }
}

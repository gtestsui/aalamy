<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateRosterAssignmentStudentPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster_assignment_student_pages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('roster_assignment_page_id');
            $table->foreign('roster_assignment_page_id','ro_as_pa_id_foreign')->references('id')->on('roster_assignment_pages')->onDelete('cascade');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');


            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_locked')->default(false);


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
        Schema::dropIfExists('student_pages');
    }
}

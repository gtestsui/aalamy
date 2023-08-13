<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateEducatorRosterStudentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educator_roster_student_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('educator_id');
            $table->foreign('educator_id')->references('id')->on('educators')->onDelete('cascade');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->unsignedBigInteger('roster_id');
            $table->foreign('roster_id')->references('id')->on('rosters')->onDelete('cascade');


            $table->enum('status',config('Roster.panel.educator_roster_student_request_statuses'))->default(config('Roster.panel.educator_roster_student_request_statuses.waiting'));
            $table->text('introductory_message')->nullable();
            $table->text('reject_cause')->nullable();
            $table->enum('from',['educator','student'])->default('educator');
            $table->enum('to',['educator','student'])->default('student');

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
        Schema::dropIfExists('educator_roster_student_requests');
    }
}

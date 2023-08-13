<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateSchoolTeacherRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_teacher_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('educator_id');
            $table->foreign('educator_id')->references('id')->on('educators')->onDelete('cascade');

            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->enum('status',config('SchoolInvitation.panel.teacher_request_statuses'))->default(config('SchoolInvitation.panel.teacher_request_statuses.waiting'));
            $table->text('introductory_message')->nullable();
            $table->text('reject_cause')->nullable();
            $table->enum('from',['educator','school']);
            $table->enum('to',['educator','school']);

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
        Schema::dropIfExists('school_teacher_requests');
    }
}

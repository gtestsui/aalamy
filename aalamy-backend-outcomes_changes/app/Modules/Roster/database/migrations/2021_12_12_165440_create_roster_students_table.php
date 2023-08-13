<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateRosterStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roster_students', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('roster_id');
            $table->foreign('roster_id')->references('id')->on('rosters')->onDelete('cascade');

            $table->unsignedBigInteger('class_student_id');
            $table->foreign('class_student_id')->references('id')->on('class_students')->onDelete('cascade');

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
        Schema::dropIfExists('roster_students');
    }
}

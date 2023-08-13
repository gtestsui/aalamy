<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class CreateStudentPageStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_page_stickers', function (Blueprint $table) {
            $table->id();

            //who create the sticker
            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedBigInteger('educator_id')->nullable();
            $table->foreign('educator_id')->references('id')->on('educators')->onDelete('cascade');



            $table->unsignedBigInteger('roster_assignment_student_page_id')->nullable();
            $table->foreign('roster_assignment_student_page_id')->references('id')->on('roster_assignment_student_pages')->onDelete('cascade');

            $table->unsignedBigInteger('sticker_id')->nullable();
            $table->foreign('sticker_id')->references('id')->on('stickers')->onDelete('cascade');

            //we have added these relations to get data directly and decrease the query count
            $table->unsignedBigInteger('page_id')->nullable();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->unsignedBigInteger('roster_assignment_id')->nullable();
            $table->foreign('roster_assignment_id')->references('id')->on('roster_assignments')->onDelete('cascade');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');


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
        Schema::dropIfExists('student_page_stickers');
    }
}

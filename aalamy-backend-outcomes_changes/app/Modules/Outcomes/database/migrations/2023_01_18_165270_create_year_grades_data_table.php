<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateYearGradesDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_grades_data', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('year_grade_template_id');
            $table->foreign('year_grade_template_id')
                ->references('id')
                ->on('year_grades_templates')
                ->onDelete('cascade');

            $table->unsignedBigInteger('student_studying_information_id');
            $table->foreign('student_studying_information_id')
                ->references('id')
                ->on('student_studying_information')
                ->onDelete('cascade');

            $table->integer('exam_degree_semester_1')->nullable();
            $table->integer('exam_degree_semester_2')->nullable();
//            $table->integer('sum')->nullable();
//            $table->integer('final_result')->nullable();

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
        Schema::dropIfExists('year_grades_data');
    }
}

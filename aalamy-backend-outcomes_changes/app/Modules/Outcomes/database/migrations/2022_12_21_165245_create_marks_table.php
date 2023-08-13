<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_studying_information_id');
            $table->foreign('student_studying_information_id')
                ->references('id')
                ->on('student_studying_information')
                ->onDelete('cascade');

            $table->unsignedBigInteger('year_grade_template_id');
            $table->foreign('year_grade_template_id')
                ->references('id')
                ->on('year_grades_templates')
                ->onDelete('cascade');

            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
                ->onDelete('cascade');

            $table->unsignedBigInteger('level_subject_id');
            $table->foreign('level_subject_id')
                ->references('id')
                ->on('level_subjects')
                ->onDelete('cascade');

            //we have repeated this field from leve_subject_rules because if the subject changed from one_field true to false so this shouldnt appear on the past years
            $table->boolean('its_one_field');
            $table->float('verbal')->nullable();//شفهي
            $table->float('jobs_and_worksheets')->nullable();//وظائف و أوراق عمل
            $table->float('activities_and_Initiatives')->nullable();//نشاطات و مبادرات
            $table->float('quiz')->nullable();//مذاكرة
            $table->float('exam')->nullable();//امتحان
            $table->float('final_mark')->nullable();//المحصلة


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
        Schema::dropIfExists('marks');
    }
}

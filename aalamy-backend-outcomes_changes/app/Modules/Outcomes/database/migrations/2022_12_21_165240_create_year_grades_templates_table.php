<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateYearGradesTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_grades_templates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('base_level_id');
            $table->foreign('base_level_id')
                ->references('id')
                ->on('base_levels')
                ->onDelete('cascade');

            $table->unsignedBigInteger('base_subject_id')->nullable();
            $table->foreign('base_subject_id')
                ->references('id')
                ->on('base_subjects')
                ->onDelete('cascade');

            $table->string('writable_subject_name')->nullable();
            $table->integer('max_degree')->nullable();
            $table->integer('failure_point')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('its_tow_section_subject')->default(false);
            $table->boolean('its_grand_total')
                ->default(false);
            $table->boolean('its_final_total')
                ->default(false);

            //like السلوك,النشاط
            $table->boolean('its_one_mark')->default(false);



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
        Schema::dropIfExists('year_grades_templates');
    }
}

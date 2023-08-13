<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableYearGradesGeneralInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('year_grades_general_info', function (Blueprint $table) {
            $table->string('directorate_of_education_in_the_province_of')->nullable();
            $table->string('school')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('student_name')->nullable();
            $table->string('the_father')->nullable();
            $table->string('the_mother')->nullable();
            $table->string('year_of_date_of_birth')->nullable();
            $table->string('month_of_date_of_birth')->nullable();
            $table->string('day_of_date_of_birth')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('year')->nullable();
            $table->string('class')->nullable();
            $table->string('division')->nullable();
            $table->string('foreign_language')->nullable();
            $table->string('the_number_is_in_the_public_record')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('teacher_name')->nullable();
            $table->string('start_educational_year')->nullable();
            $table->string('end_educational_year')->nullable();
            $table->string('instructional_directions_for_the_teacher')->nullable();
            $table->string('manager_notes_semester_1')->nullable();
            $table->string('manager_notes_semester_2')->nullable();
            $table->string('day_of_manager_signature_date1')->nullable();
            $table->string('month_of_manager_signature_date1')->nullable();
            $table->string('year_of_manager_signature_date1')->nullable();
            $table->string('day_of_manager_signature_date2')->nullable();
            $table->string('month_of_manager_signature_date2')->nullable();
            $table->string('year_of_manager_signature_date2')->nullable();
            $table->string('hijri_year1')->nullable();//عام هجري1
            $table->string('hijri_year2')->nullable();//عام هجري2
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('year_grades_general_info', function (Blueprint $table) {
            $table->dropColumn('directorate_of_education_in_the_province_of');
            $table->dropColumn('school');
            $table->dropColumn('serial_number');
            $table->dropColumn('student_name');
            $table->dropColumn('the_father');
            $table->dropColumn('the_mother');
            $table->dropColumn('year_of_date_of_birth');
            $table->dropColumn('month_of_date_of_birth');
            $table->dropColumn('day_of_date_of_birth');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('year');
            $table->dropColumn('class');
            $table->dropColumn('division');
            $table->dropColumn('foreign_language');
            $table->dropColumn('the_number_is_in_the_public_record');
            $table->dropColumn('manager_name');
            $table->dropColumn('teacher_name');
            $table->dropColumn('start_educational_year');
            $table->dropColumn('end_educational_year');
            $table->dropColumn('instructional_directions_for_the_teacher');
            $table->dropColumn('manager_notes_semester_1');
            $table->dropColumn('manager_notes_semester_2');
            $table->dropColumn('day_of_manager_signature_date1');
            $table->dropColumn('month_of_manager_signature_date1');
            $table->dropColumn('year_of_manager_signature_date1');
            $table->dropColumn('day_of_manager_signature_date2');
            $table->dropColumn('month_of_manager_signature_date2');
            $table->dropColumn('year_of_manager_signature_date2');
            $table->dropColumn('hijri_year1');
            $table->dropColumn('hijri_year2');
        });
    }
}

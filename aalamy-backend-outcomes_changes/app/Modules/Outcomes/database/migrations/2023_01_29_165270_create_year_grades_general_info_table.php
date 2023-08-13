<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateYearGradesGeneralInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_grades_general_info', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_studying_information_id');
            $table->foreign('student_studying_information_id')
                ->references('id')
                ->on('student_studying_information')
                ->onDelete('cascade');

            $table->string('school_manager_notes')->nullable();// ملاحظات مدير المدرسة
            $table->string('school_manager_notes_semester_1')->nullable();// ملاحظات مدير المدرسة
            $table->string('school_manager_notes_semester_2')->nullable();// ملاحظات مدير المدرسة
            $table->string('parent_notes')->nullable();//ملاحظات ولي الطالب

            //جدول دوام التلميذ
            $table->string('actual_attendee_hours_semester_1')->nullable();//الدوام الفعلي الفصل الاول
            $table->string('actual_attendee_hours_semester_2')->nullable();//الدوام الفعلي الفصل الثاني

            $table->string('student_attendee_hours_semester_1')->nullable();//الدوام الفعلي الفصل الاول
            $table->string('student_attendee_hours_semester_2')->nullable();//الدوام الفعلي الفصل الثاني

            $table->string('excused_absence_semester_1')->nullable();//الغياب المبرر الفصل الاول
            $table->string('excused_absence_semester_2')->nullable();//الغياب المبرر الفصل الثاني

            $table->string('unexcused_absence_semester_1')->nullable();//الغياب الغير مبرر الفصل الاول
            $table->string('unexcused_absence_semester_2')->nullable();//الغياب الغير مبرر الفصل الثاني


            //التوحيهات التربوية للمعلم
            $table->tinyText('instructional_directions_for_the_teacher')->nullable();//التوجيهات التربوية للمعلم

            //نتيجة التلميذ
            $table->string('class_success')->nullable();//نجاح الى الصف
            $table->string('failing_class')->nullable();//رسوب في الصف
            $table->string('transfer_to_class_because_he_is_repeater')->nullable();//نقل الى الصف لانه معيد
            $table->string('transfer_to_class_because_exhaust_the_years_of_failure')->nullable();//نقل الى الصف لاستنفاذ سنوات الرسوب

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
        Schema::dropIfExists('year_grades_general_info');
    }
}

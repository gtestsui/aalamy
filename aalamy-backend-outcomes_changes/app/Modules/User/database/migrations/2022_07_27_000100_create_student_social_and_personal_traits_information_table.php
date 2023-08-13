<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSocialAndPersonalTraitsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_social_and_personal_traits_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id','st_oth_perso_and_social_fk')->references('id')->on('students')->onDelete('cascade');

            $table->boolean('trouble_paying_attention')->nullable();//لديه صعوبة في الانتباه
            $table->boolean('trouble_distinguishing')->nullable();//لديه صعوبة في التميز
            $table->boolean('weak_memory')->nullable();//لديه ضعف في الذاكرة
            $table->boolean('behavioral_abnormalities')->nullable();//لديه انحرافات سلوكية
            $table->boolean('hyperactivity')->nullable();//فرط النشاط
            $table->boolean('tends_to_behave_aggressively')->nullable();//يميل الى التصرف بعدوانية
            $table->boolean('introvert')->nullable();//انطوائي
            $table->boolean('difficulty_with_learning')->nullable();//صعوبة تجاه التعلم
            $table->boolean('it_takes_a_lot_to_motivate_him')->nullable();//يحتاج الكثير لاثارة دافعيته
            $table->boolean('trust_himself')->nullable();//يثق بنفسه
            $table->boolean('take_responsibility')->nullable();//يتحمل المسؤولية
            $table->boolean('respects_the_order')->nullable();//يحترم النظام
            $table->boolean('accept_criticism_and_correct_mistakes')->nullable();//يقبل النقد ويصحح الاخطاء
            $table->boolean('cooperating')->nullable();//متعاون
            $table->boolean('he_expresses_his_opinion_boldly')->nullable();//يبادر في ابداء رأيه بجرأة
            $table->boolean('controlling_and_showing_off')->nullable();//يميل الى السيطرة وحب الظهور
            $table->boolean('contribute_to_activities')->nullable();//يساهم في النشاطات
            $table->boolean('he_perseveres_in_his_work')->nullable();//يثابر في اداء عمله
            $table->boolean('maintains_public_facilities')->nullable();//يحافظ على المرافق العامة
            $table->boolean('respect_the_rules_and_regulations_of_the_school')->nullable();//يحترم النظام والقوانين في المدرسة
            $table->boolean('suffers_from_jealousy')->nullable();//يعاني من الغيرة
            $table->boolean('committed')->nullable();//ملتزم
            $table->boolean('leading')->nullable();//قيادي
            $table->boolean('initiative')->nullable();//مبادر
            $table->boolean('careful_observation')->nullable();//دقيق الملاحظة
            $table->boolean('able_to_simulate')->nullable();//قادر على المحاكاة
            $table->boolean('creator')->nullable();//مبدع
            $table->boolean('self_made')->nullable();//عصامي
            $table->boolean('disciplined')->nullable();//منضبط
            $table->boolean('hard_working')->nullable();//دؤوب على العمل
            $table->boolean('emotionally_balanced')->nullable();//متزن انفعاليا
            $table->boolean('tends_to_rebel')->nullable();//يميل الى التمرد
            $table->boolean('artistic_hobbies')->nullable();//لديه هوايات فنية
            $table->boolean('sports_hobbies')->nullable();//هوايات رياضية
            $table->boolean('other_hobbies')->nullable();//هوايات اخرى

            $table->string('another_traits')->nullable();

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
        Schema::dropIfExists('student_social_and_personal_traits_information');
    }
}

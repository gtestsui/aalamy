<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Classes\ApplicationModules;


class CreateNotificationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->integer('type_num');
            $table->timestamps();
        });

        //type_num 1
        DB::table('notifications_types')->insert([
            'name_en' => 'joining school student request',
            'name_ar' => 'طلب انضمام طالب للمدرسة',
            'type_num' => configFromModule('panel.notification_types.school_student_request',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 2
        DB::table('notifications_types')->insert([
            'name_en' => 'approve or reject joining school request',
            'name_ar' => 'قبول او رفض طلب انضمام للمدرسة',
            'type_num' => configFromModule('panel.notification_types.approve_or_reject_school_student_request',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 3
        DB::table('notifications_types')->insert([
            'name_en' => 'approve your post',
            'name_ar' => 'قبول منشورك',
            'type_num' => configFromModule('panel.notification_types.approve_your_post',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 4
        DB::table('notifications_types')->insert([
            'name_en' => 'approve your survey',
            'name_ar' => 'قبول استبيانك',
            'type_num' => configFromModule('panel.notification_types.approve_your_survey',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 5
        DB::table('notifications_types')->insert([
            'name_en' => 'educator roster student request',
            'name_ar' => 'طلب انضمام الى الحصة',
            'type_num' => configFromModule('panel.notification_types.educator_roster_student_request',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 6
        DB::table('notifications_types')->insert([
            'name_en' => 'new event',
            'name_ar' => 'تمت اضافة حدث جديد',
            'type_num' => configFromModule('panel.notification_types.new_event',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 7
        DB::table('notifications_types')->insert([
            'name_en' => 'send feedback about student',
            'name_ar' => 'ارسال التقييم عن الطالب',
            'type_num' => configFromModule('panel.notification_types.send_feedback_about_student',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 8
        DB::table('notifications_types')->insert([
            'name_en' => 'manual notification',
            'name_ar' => 'اشعار يدوي',
            'type_num' => configFromModule('panel.notification_types.manual_notification',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 9
        DB::table('notifications_types')->insert([
            'name_en' => 'add contact us',
            'name_ar' => 'تمت ارسال طلب تواصل معنا',
            'type_num' => configFromModule('panel.notification_types.contact_us',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 10
        DB::table('notifications_types')->insert([
            'name_en' => 'new achievement ',
            'name_ar' => 'تمت اضافة انجاز جديد ',
            'type_num' => configFromModule('panel.notification_types.new_achievement',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 11
        DB::table('notifications_types')->insert([
            'name_en' => 'new achievement waiting publish',
            'name_ar' => 'تمت اضافة انجاز جديد بانتظار موافقتك لنشره',
            'type_num' => configFromModule('panel.notification_types.new_achievement_waiting_publish',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 12
        DB::table('notifications_types')->insert([
            'name_en' => 'there is an event target you has been updated',
            'name_ar' => 'تم تعديل حدث قد يهمك ',
            'type_num' => configFromModule('panel.notification_types.updated_event',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 13
        DB::table('notifications_types')->insert([
            'name_en' => 'there is a meeting target you has been added',
            'name_ar' => 'تمت اضافة جلسة جديدة',
            'type_num' => configFromModule('panel.notification_types.new_meeting',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 14
        DB::table('notifications_types')->insert([
            'name_en' => 'there is a new post waiting your approve',
            'name_ar' => 'تمت اضافة منشور جديد ينتظر موافقتك ',
            'type_num' => configFromModule('panel.notification_types.new_post_waiting_approve',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 15
        DB::table('notifications_types')->insert([
            'name_en' => 'there is a new survey waiting your approve',
            'name_ar' => 'تمت اضافة استبيان جديد ينتظر موافقتك ',
            'type_num' => configFromModule('panel.notification_types.new_survey_waiting_approve',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 16
        DB::table('notifications_types')->insert([
            'name_en' => 'there is a new quiz',
            'name_ar' => 'تمت اضافة اختبار جديد  ',
            'type_num' => configFromModule('panel.notification_types.new_quiz',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 17
        DB::table('notifications_types')->insert([
            'name_en' => 'there is a new help request',
            'name_ar' => 'تمت اضافة طلب مساعدة جديد  ',
            'type_num' => configFromModule('panel.notification_types.help_request',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 18
        DB::table('notifications_types')->insert([
            'name_en' => 'there is a new check answer request',
            'name_ar' => 'تمت اضافة طلب التحقق من الاجابة  ',
            'type_num' => configFromModule('panel.notification_types.check_answer_request',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 19
        DB::table('notifications_types')->insert([
            'name_en' => 'joining school teacher request',
            'name_ar' => 'طلب انضمام استاذ للمدرسة',
            'type_num' => configFromModule('panel.notification_types.school_teacher_request',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);
    
    
        //type_num 20
        DB::table('notifications_types')->insert([
            'name_en' => 'approve_or_reject_school_teacher_request',
            'name_ar' => 'قبول او رفض طلب انضمام استاذ',
            'type_num' => configFromModule('panel.notification_types.approve_or_reject_school_teacher_request',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);

        //type_num 21
        DB::table('notifications_types')->insert([
            'name_en' => 'new_assignment_assigned',
            'name_ar' => 'تم اسناد واجب جديد',
            'type_num' => configFromModule('panel.notification_types.new_assignment_assigned',ApplicationModules::NOTIFICATION_MODULE_NAME),
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_types');
    }
}

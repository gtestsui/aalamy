<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes;



use Illuminate\Support\Facades\DB;

class SubscriptionPlanModuleMigrationServices
{


    public static function createRequiredData(){
        self::initializeTeachersCountModule();//1
        self::initializeStudentsCountModule();//2,3
        self::initializeRostersCountModule();//4,5
        self::initializeAssignmentsCountModule();//6,7
        self::initializeImportStudentFromExcelModule();//8,9
        self::initializeMeetingsCountModule();//10,11
        self::initializeMeetingsAttendeesCountModule();//12,13
        self::initializeAddImagesToAssignmentModule();//14,15
        self::initializeAddVideosToAssignmentModule();//16,17
        self::initializeAddVoicesToAssignmentModule();//18,19
        self::initializeAddFilesToAssignmentModule();//20,21
        self::initializeAddQuestionsToAssignmentModule();//22,23
        self::initializeAddMultipleChoiceQuestionsToAssignmentModule();//24,25
        self::initializeAddFillTextChoiceQuestionsToAssignmentModule();//26,27
        self::initializeAddFillInBlankChoiceQuestionsToAssignmentModule();//28,29
        self::initializeAddMatchingChoiceQuestionsToAssignmentModule();//30,31
        self::initializeAddTrueFalseQuestionsToAssignmentModule();//32,33
        self::initializeAddJumbleSentencesQuestionsToAssignmentModule();//34,35
        self::initializeAddOrderingQuestionsToAssignmentModule();//36,37
        self::initializeMathEditorModule();//38,39
        self::initializeKnowOnlineStudentsInAssignmentsModule();//40,41
        self::initializeMeetingDurationModule();//42,43
        self::initializeDownloadAttendanceFileModule();//44,45
        self::initializeManualNotificationModule();//46,47
        self::initializeStudentAchievementModule();//48,49
        self::initializeQuizModule();//50,51
        self::initializeAssignmentEditor();//52,53



    }

    private static function initializeTeachersCountModule(){
        //teachers_count
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*1*/DB::table('modules')
                    ->where('identify',getModuleIdentify('teachers_count'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//teachers_count
                'number' => 1000,
            ],
        ]);
    }

    private static function initializeStudentsCountModule(){
        //students_count
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*2*/DB::table('modules')
                    ->where('identify',getModuleIdentify('students_count'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//students_count
                'number' => 1000,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*3*/DB::table('modules')
                    ->where('identify',getModuleIdentify('students_count'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//students_count
                'number' => 1000,
            ],
        ]);
    }


    private static function initializeRostersCountModule(){
        //rosters_count
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*4*/DB::table('modules')
                    ->where('identify',getModuleIdentify('rosters_count'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//rosters_count
                'number' => 1000,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*5*/DB::table('modules')
                    ->where('identify',getModuleIdentify('rosters_count'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//rosters_count
                'number' => 1000,
            ],
        ]);
    }


    private static function initializeAssignmentsCountModule(){
        //assignments_count
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*6*/DB::table('modules')
                    ->where('identify',getModuleIdentify('assignments_count'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//rosters_count
                'number' => 1000,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*7*/DB::table('modules')
                    ->where('identify',getModuleIdentify('assignments_count'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//rosters_count
                'number' => 1000,
            ],
        ]);
    }

    private static function initializeImportStudentFromExcelModule(){
        //import_students_from_excel
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*8*/DB::table('modules')
                    ->where('identify',getModuleIdentify('import_students_from_excel'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//import_students_from_excel
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*9*/DB::table('modules')
                    ->where('identify',getModuleIdentify('import_students_from_excel'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//import_students_from_excel
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeMeetingsCountModule(){
        //meetings_count
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*10*/DB::table('modules')
                    ->where('identify',getModuleIdentify('meetings_count'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//rosters_count
                'number' => 1000,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*11*/DB::table('modules')
                    ->where('identify',getModuleIdentify('meetings_count'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//rosters_count
                'number' => 1000,
            ],
        ]);
    }

    private static function initializeMeetingsAttendeesCountModule(){
        //meeting_attendees_count
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*12*/DB::table('modules')
                    ->where('identify',getModuleIdentify('meeting_attendees_count'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//rosters_count
                'number' => 1000,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*13*/DB::table('modules')
                    ->where('identify',getModuleIdentify('meeting_attendees_count'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//rosters_count
                'number' => 1000,
            ],
        ]);
    }

    private static function initializeAddImagesToAssignmentModule(){
        //add_images_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*14*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_images_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_images_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*15*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_images_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_images_to_assignment
                'can_use' => true,
            ],
        ]);
    }


    private static function initializeAddVideosToAssignmentModule(){
        //add_videos_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*16*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_videos_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_videos_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*17*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_videos_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_videos_to_assignment
                'can_use' => true,
            ],
        ]);
    }


    private static function initializeAddVoicesToAssignmentModule(){
        //add_voices_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*18*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_voices_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_voices_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*19*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_voices_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_voices_to_assignment
                'can_use' => true,
            ],
        ]);
    }


    private static function initializeAddFilesToAssignmentModule(){
        //add_files_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*20*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_files_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_files_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*21*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_files_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_files_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAddQuestionsToAssignmentModule(){
        //add_questions_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*22*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_questions_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*23*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_questions_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAddMultipleChoiceQuestionsToAssignmentModule(){
        //add_multiple_choice_questions_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*22*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_multiple_choice_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_multiple_choice_questions_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*23*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_multiple_choice_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_multiple_choice_questions_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAddFillTextChoiceQuestionsToAssignmentModule(){
        //add_fill_text_questions_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*22*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_fill_text_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_fill_text_questions_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*23*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_fill_text_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_fill_text_questions_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAddFillInBlankChoiceQuestionsToAssignmentModule(){
        //add_fill_in_blank_questions_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*22*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_fill_in_blank_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_fill_in_blank_questions_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*23*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_fill_in_blank_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_fill_in_blank_questions_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAddMatchingChoiceQuestionsToAssignmentModule(){
        //add_matching_questions_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*22*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_matching_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_matching_questions_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*23*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_matching_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_matching_questions_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAddTrueFalseQuestionsToAssignmentModule(){
        //add_true_false_questions_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*22*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_true_false_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_true_false_questions_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*23*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_true_false_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_true_false_questions_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAddJumbleSentencesQuestionsToAssignmentModule(){
        //add_jumble_sentences_questions_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*22*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_jumble_sentences_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_jumble_sentences_questions_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*23*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_jumble_sentences_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_jumble_sentences_questions_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAddOrderingQuestionsToAssignmentModule(){
        //add_ordering_questions_to_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*22*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_ordering_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//add_ordering_questions_to_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*23*/DB::table('modules')
                    ->where('identify',getModuleIdentify('add_ordering_questions_to_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//add_ordering_questions_to_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeMathEditorModule(){
        //math_editor
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*24*/DB::table('modules')
                    ->where('identify',getModuleIdentify('math_editor'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//math_editor
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*25*/DB::table('modules')
                    ->where('identify',getModuleIdentify('math_editor'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//math_editor
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeKnowOnlineStudentsInAssignmentsModule(){
        //know_online_students_in_assignment
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*24*/DB::table('modules')
                    ->where('identify',getModuleIdentify('know_online_students_in_assignment'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//know_online_students_in_assignment
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*25*/DB::table('modules')
                    ->where('identify',getModuleIdentify('know_online_students_in_assignment'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//know_online_students_in_assignment
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeMeetingDurationModule(){
        //meeting_duration
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*24*/DB::table('modules')
                    ->where('identify',getModuleIdentify('meeting_duration'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//meeting_duration
                'number' => 1000,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*25*/DB::table('modules')
                    ->where('identify',getModuleIdentify('meeting_duration'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//meeting_duration
                'number' => 1000,
            ],
        ]);
    }

    private static function initializeDownloadAttendanceFileModule(){
        //download_attendance_file
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*24*/DB::table('modules')
                    ->where('identify',getModuleIdentify('download_attendance_file'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//meeting_duration
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*25*/DB::table('modules')
                    ->where('identify',getModuleIdentify('download_attendance_file'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//meeting_duration
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeManualNotificationModule(){
        //manual_notification
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*24*/DB::table('modules')
                    ->where('identify',getModuleIdentify('manual_notification'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//meeting_duration
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*25*/DB::table('modules')
                    ->where('identify',getModuleIdentify('manual_notification'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//meeting_duration
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeStudentAchievementModule(){
        //student_achievement
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => /*24*/DB::table('modules')
                    ->where('identify',getModuleIdentify('student_achievement'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//meeting_duration
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => /*25*/DB::table('modules')
                    ->where('identify',getModuleIdentify('student_achievement'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//meeting_duration
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeQuizModule(){
        //student_achievement
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => DB::table('modules')
                    ->where('identify',getModuleIdentify('quiz'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//quiz
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => DB::table('modules')
                    ->where('identify',getModuleIdentify('quiz'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//quiz
                'can_use' => true,
            ],
        ]);
    }

    private static function initializeAssignmentEditor(){
        //assignment_editor
        DB::table('subscription_plan_modules')->insert([
            [
                'subscription_plan_id' => 1,//school_plan
                'module_id' => DB::table('modules')
                    ->where('identify',getModuleIdentify('assignment_editor'))
                    ->where('type',getModuleOwnerType('school'))
                    ->first()->id,//quiz
                'can_use' => true,
            ],
            [
                'subscription_plan_id' => 2,//educator_plan
                'module_id' => DB::table('modules')
                    ->where('identify',getModuleIdentify('assignment_editor'))
                    ->where('type',getModuleOwnerType('educator'))
                    ->first()->id,//quiz
                'can_use' => true,
            ],
        ]);
    }


}

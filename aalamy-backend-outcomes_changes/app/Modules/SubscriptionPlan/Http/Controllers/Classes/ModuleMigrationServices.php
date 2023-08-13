<?php


namespace Modules\SubscriptionPlan\Http\Controllers\Classes;



use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Support\Facades\DB;

class ModuleMigrationServices
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
        //teachers_count => 1
        $modulePlanName = 'teachers_count';
        DB::table('modules')->insert([
            //school_module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('school'),
            ]
        ]);
    }

    private static function initializeStudentsCountModule(){
        //students_count => 2,3
        $modulePlanName = 'students_count';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }


    private static function initializeRostersCountModule(){
        //rosters_count => 4,5
        $modulePlanName = 'rosters_count';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }


    private static function initializeAssignmentsCountModule(){
        //assignments_count => 6,7
        $modulePlanName = 'assignments_count';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeImportStudentFromExcelModule(){
        //import_students_from_excel => 8,9
        $modulePlanName = 'import_students_from_excel';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeMeetingsCountModule(){
        //meetings_count => 10,11
        $modulePlanName = 'meetings_count';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeMeetingsAttendeesCountModule(){
        //meeting_attendees_count => 12,13
        $modulePlanName = 'meeting_attendees_count';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddImagesToAssignmentModule(){
        //add_images_to_assignment => 14,15
        $modulePlanName = 'add_images_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }


    private static function initializeAddVideosToAssignmentModule(){
        //add_videos_to_assignment => 16,17
        $modulePlanName = 'add_videos_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }


    private static function initializeAddVoicesToAssignmentModule(){
        //add_voices_to_assignment => 18,19
        $modulePlanName = 'add_voices_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddFilesToAssignmentModule(){
        //add_files_to_assignment => 20,21
        $modulePlanName = 'add_files_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }


    private static function initializeAddQuestionsToAssignmentModule(){
        //add_questions_to_assignment => 22,23
        $modulePlanName = 'add_questions_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddMultipleChoiceQuestionsToAssignmentModule(){
        //add_multiple_choice_questions_to_assignment => 24,25
        $modulePlanName = 'add_multiple_choice_questions_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddFillTextChoiceQuestionsToAssignmentModule(){
        //add_fill_text_questions_to_assignment => 26,27
        $modulePlanName = 'add_fill_text_questions_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddFillInBlankChoiceQuestionsToAssignmentModule(){
        //add_fill_in_blank_questions_to_assignment => 28,29
        $modulePlanName = 'add_fill_in_blank_questions_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddMatchingChoiceQuestionsToAssignmentModule(){
        //add_matching_questions_to_assignment => 30,31
        $modulePlanName = 'add_matching_questions_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddTrueFalseQuestionsToAssignmentModule(){
        //add_true_false_questions_to_assignment => 32,33
        $modulePlanName = 'add_true_false_questions_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddJumbleSentencesQuestionsToAssignmentModule(){
        //add_jumble_sentences_questions_to_assignment => 34,35
        $modulePlanName = 'add_jumble_sentences_questions_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAddOrderingQuestionsToAssignmentModule(){
        //add_ordering_questions_to_assignment => 36,37
        $modulePlanName = 'add_ordering_questions_to_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeMathEditorModule(){
        //math_editor => 38,39
        $modulePlanName = 'math_editor';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeKnowOnlineStudentsInAssignmentsModule(){
        //math_editor => 40,41
        $modulePlanName = 'know_online_students_in_assignment';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeMeetingDurationModule(){
        //meeting_duration => 42,43
        $modulePlanName = 'meeting_duration';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_limit_number'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeDownloadAttendanceFileModule(){
        //meeting_duration => 44,45
        $modulePlanName = 'download_attendance_file';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeManualNotificationModule(){
        //meeting_duration => 46,47
        $modulePlanName = 'manual_notification';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeStudentAchievementModule(){
        //student_achievement => 48,49
        $modulePlanName = 'student_achievement';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }


    private static function initializeQuizModule(){
        //quiz => 50,51
        $modulePlanName = 'quiz';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }

    private static function initializeAssignmentEditor(){
        //assignment_editor => 52,53
        $modulePlanName = 'assignment_editor';
        DB::table('modules')->insert([
            //school module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('school'),
            ],
            //educator module
            [
                'identify' => getModuleIdentify($modulePlanName),
                'name' => $modulePlanName,
                'description' => 'description',
                'usage_type' => getModuleUsageType('by_use'),
                'type' => getModuleOwnerType('educator'),
            ]
        ]);
    }



}

<?php

return [
    'panel' => [
        'billing_cycles'=>[
            'yearly' => 'yearly',
            'monthly' => 'monthly',
            'quarterly' => 'quarterly',
            'semi_yearly' => 'semi_yearly',
            'free' => 'free',
            'fixed_count_of_days' => 'fixed_count_of_days'
        ],

        'subscription_plan_types' => ['school','educator'],


        'billing_cycles_in_days'=>[
            'yearly' => 365,
            'semi_yearly' => 183,
            'quarterly' => 91,
            'monthly' => 30,
            'free' => 1000000,
        ],

        'paypal' => [
            'client_id' => env('PAYPAL_CLIENT_ID','AULE9iQwwWhwdRP_LAbT44bKK-5jao0w-mFiq23edN0cRvG2vnwWF5oz4WhZVVXsLZ_oLVNX7MorG2af'),
            'secret' => env('PAYPAL_SECRET','EEFf76uZKIOUAXxgkANf8jioV-91NKmeDyZnGxloZ7T9LkKW0GEnAoGE8-K49tkwyHuvNJcN_9HdPSu2'),
            'settings' => [
                'mode' => env('PAYPAL_MODE','sandbox'),
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => storage_path() . '/logs/laravel.log',
                'log.LogLevel' => 'ERROR'
            ],
        ],

        'encrypt_method' => 'AES-256-ECB',


        'module_types' => [
            'school'=>'school',
            'educator'=>'educator'
        ],


        'modules_usage_types' => [
            'by_use' => 'by_use',
            'by_limit_number' => 'by_limit_number',
        ],

        'modules' => [
            'teachers_count' => 1,
            'students_count' => 2,
            'rosters_count' => 3,
            'assignments_count' => 4,
            'import_students_from_excel' => 5,
            'meetings_count' => 6,
            'meeting_attendees_count' => 7,
            'add_images_to_assignment' => 8,//this checked just from front
            'add_videos_to_assignment' => 9,//this checked just from front
            'add_voices_to_assignment' => 10,//this checked just from front
            'add_files_to_assignment' => 11,//this checked just from front
            'add_questions_to_assignment' => 12,//this checked just from front
            'add_multiple_choice_questions_to_assignment' => 13,//this checked just from front
            'add_fill_text_questions_to_assignment' => 14,//this checked just from front
            'add_fill_in_blank_questions_to_assignment' => 15,//this checked just from front
            'add_matching_questions_to_assignment' => 16,//this checked just from front
            'add_true_false_questions_to_assignment' => 17,//this checked just from front
            'add_jumble_sentences_questions_to_assignment' => 18,//this checked just from front
            'add_ordering_questions_to_assignment' => 19,//this checked just from front
            'math_editor' => 20,//this checked just from front
            'know_online_students_in_assignment' => 21,//this checked just from front
            'meeting_duration' => 22,
            'download_attendance_file' => 23,
            'manual_notification' => 24,
            'student_achievement' => 25,
            'quiz' => 26,
            'assignment_editor' => 27,

        ],


    ]
];

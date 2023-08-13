<?php

return [
    'panel' => [
        'notification_types'=>[
          'school_student_request' => 1,
          'approve_or_reject_school_student_request' => 2,
          'approve_your_post' => 3,
          'approve_your_survey' => 4,
          'educator_roster_student_request' => 5,
          'new_event' => 6,
          'send_feedback_about_student' => 7,
          'manual_notification' => 8,
          'contact_us' => 9,
          'new_achievement' => 10,
          'new_achievement_waiting_publish' => 11,
          'updated_event' => 12,
          'new_meeting' => 13,
          'new_post_waiting_approve' => 14,
          'new_survey_waiting_approve' => 15,
          'new_quiz' => 16,
          'help_request' => 17,
          'check_answer_request' => 18,
          'school_teacher_request' => 19,
          'approve_or_reject_school_teacher_request' => 20,
          'new_assignment_assigned' => 21,


        ],

        'send_by_types' =>[
          'email' => 'email',
          'push' => 'push',
        ],


        'manual_notification_priority' => [
            0,1,2
        ]

    ],
];

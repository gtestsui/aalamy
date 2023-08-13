<?php

return [
    'panel' => [

        'post_priority_names'=>[
            'low','medium','height'
        ],

        'post_priority_values'=>[
            0,1,2
        ],

        /*'post_priority_values'=>[
            'low'=>0,'medium'=>1,'height'=>2
        ],*/

        'survey_priority_names'=>[
            'low','medium','height'
        ],

        /*'survey_priority_names'=>[
            0=>'low',1=>'medium',2=>'height'
        ],*/

        'survey_priority_values'=>[
            0,1,2
        ],

        'accounts_can_create_post'=>[
            'school','educator','student','parent'
        ],

        'accounts_can_create_survey'=>[
            'school','educator','student','parent'
        ],


        'survey_question_types' => [
            'choice'=>'choice','fill_text'=>'fill_text'
        ],

        'reply_count_per_page' => 10,
        'post_count_per_page' => 10,
        'survey_count_per_page' => 10,
        'limited_survey_user_answer_count_per_question' => 6,
        'survey_user_answer_count_per_question' => 10,


    ],
];

<?php

return [
    'panel' => [
        'question_types' => [
            'multi_choice' => 'multi_choice',
            'true_false' => 'true_false',
            'fill_in_blank' => 'fill_in_blank',
            'jumble_sentence' => 'jumble_sentence',
            'fill_text' => 'fill_text',
            'matching' => 'matching',
            'ordering' => 'ordering'
        ],

        'question_types_relations' => [
            'multi_choice' => 'MultiChoices',
            'true_false' => 'TrueFalse',
            'fill_in_blank' => 'FillInBlanks',
            'jumble_sentence' => 'JumbleSentences',
            'fill_text' => 'FillTexts',
            'matching' => 'MatchingLeftList.RightListRecords',
            'ordering' => 'Ordering'
        ],

        'question_difficult_level' => [
          'low' => 0,'medium' =>1,'height' => 2
        ],

        'question_share_types_with_library' => [
            'private' => 'private',
            'school' => 'school',
            'my_private_student' => 'my_private_student',
            'public' => 'public'
        ],

    ],
];

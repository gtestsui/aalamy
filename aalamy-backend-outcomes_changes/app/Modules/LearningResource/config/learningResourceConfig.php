<?php

return [
    'panel' => [

        'learning_resource_file_types' => [
            'file' => 'file',
            'video' => 'video',
            'picture' => 'picture',
            'audio' => 'audio',
            'link' => 'link',
        ],

        'learning_resource_read_share_types' => [
            'private' => 'private',
            'school' => 'school',
            'my_private_student' => 'my_private_student',
            'public' => 'public'
        ],

        'learning_resource_write_share_types' => [
            'private' => 'private',
            'school' => 'school',
        ],

        'educator_share_type_priority' => [
            'private'=>0,
            'my_private_student'=>1,
            'public'=>2,
        ],

        'school_share_type_priority' => [
            'private'=>0,
            'school'=>1,
            'public'=>2,
        ],

        'generate_assignment_file_type_default' => 'pdf',

        'topic_content_types' => [
            'topics'=>'topics',
            'learning_resources'=>'learning_resources'
        ],

        'topic_access_types' => [
            'read' => 'read',
            'write' => 'write',
        ]

    ],
];

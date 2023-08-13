<?php

return [
    'panel' => [
        'teacher_request_statuses' => [
            'approved' => 'approved',
            'waiting'  => 'waiting' ,
            'rejected' => 'rejected',
        ],

        'student_request_statuses' => [
            'approved' => 'approved',
            'waiting'  => 'waiting' ,
            'rejected' => 'rejected',
        ],

        'request_types' => [
            'received','sent'
        ],

        // 'teacher_invitation_link' => url('/').'/#/auth/register/teacher/invite/',
           'teacher_invitation_link' => 'https://site.aalamy.org'.'/#/auth/register/teacher/invite/',


        'requests_paginate_num' => 10,
    ],
];

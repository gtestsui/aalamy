<?php

return [
    'panel' => [

        'auth_token_name' => 'my_token',

        'account_confirmation_code_long_time' => 15,
        'logged_devices_count' => '4',
        'login_services' => [
          'google' => 'google',
          'apple' => 'apple',
        ],
        'register_account_types'=>[
            'educator',
            'parent',
            'student',
            'school',
//            'teacher'
        ],
        'all_account_types'=>[
            'educator' => 'educator',
            'parent' => 'parent',
            'student' => 'student',
            'school'=> 'school',
//            'teacher',
            'superAdmin' => 'superAdmin',

            'itDirectorate' => 'itDirectorate',
            'educationDirectorate' => 'educationDirectorate',
            'supervisionDirectorate' => 'supervisionDirectorate',
            'examDirectorate' => 'examDirectorate',
        ],
        'admins' => [
            'superAdmin' => 'superAdmin',

            'itDirectorate' => 'itDirectorate',
            'educationDirectorate' => 'educationDirectorate',
            'supervisionDirectorate' => 'supervisionDirectorate',
            'examDirectorate' => 'examDirectorate',
        ],
        // 'add_child_to_parent_link'=> url('/').'/#/dashboard/parent/childs/add/',
           'add_child_to_parent_link'=> 'https://site.aalamy.org'.'/#/dashboard/parent/childs/add/',


        'register_url_in_front'=>'http://front.com',
        'login_by_service_url_in_front'=>'http://front.com/loginByService',
        'confirmation_code_length' => 6,


        'genders' => [
            'female','male'
        ],

        'user_device_types' => [
            'mobile','desktop'
        ],

        'resend_account_confirmation_code_attempts' => 3,

        'super_admin_api_prefix' => 'super-admin',

    ],
];

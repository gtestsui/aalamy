<?php
//const DATE_FORMAT = 'Y/m/d';
//const TIME_FORMAT = 'H:i:s';
//
 $DATE_FORMAT = 'Y/m/d';
 $TIME_FORMAT = 'H:i:s';
 $ds = DIRECTORY_SEPARATOR;
$default_disk_path = public_path('/');
//don't make it end with slash '/'
$default_store_path_inside_default_disk = env('DEFAULT_STORAGE_PATH_INSIDE_DEFAULT_DISK');
//$default_full_storage_path = public_path($default_store_path_inside_default_disk);
return[

    'app_front_url' => env('APP_FRONT_URL'),

    'date_format' => $DATE_FORMAT,
    'time_format' => $TIME_FORMAT,


    'standard_date_time_format' => $DATE_FORMAT.' '.$TIME_FORMAT,

    'site_languages'=>[
        'en' => 'en',
        'ar' => 'ar',
    ],

    'api_global_middleware' => [
        'apiLang','decryptRouteParameter','defaultTimezone','catchDataFromHeader','request_logger'
    ],

	'api_global_middleware_for_admin' => [
        'superAdmin','removeWithoutDeletedItemsScope'
    ],

    'timezone' => 'UTC',


    'admin_paginate_num' => 10,
    'super_admin_api_prefix' => 'super-admin',
    'super_admin_controllers_folder_name' => 'SuperAdminControllers',


    'default_disk_path' => $default_disk_path,
    'default_store_path_inside_default_disk' => $default_store_path_inside_default_disk,
//    'default_full_storage_path' => $default_full_storage_path,

];

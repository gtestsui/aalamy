<?php

return [
    'client_id' => env('PAYPAL_CLIENT_ID','AULE9iQwwWhwdRP_LAbT44bKK-5jao0w-mFiq23edN0cRvG2vnwWF5oz4WhZVVXsLZ_oLVNX7MorG2af'),
    'secret' => env('PAYPAL_SECRET','EEFf76uZKIOUAXxgkANf8jioV-91NKmeDyZnGxloZ7T9LkKW0GEnAoGE8-K49tkwyHuvNJcN_9HdPSu2'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/laravel.log',
        'log.LogLevel' => 'ERROR'
    ),
];

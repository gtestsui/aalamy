<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [

//        'client_id' => '345186459865-6dicsb2orpmdpubseqsvemjed0rv2ae8.apps.googleusercontent.com',
//
//        'client_secret' => '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',
//
//        'redirect' => 'http://localhost:8000/auth/google/callback',
//
        'client_id' => '1070478041139-51p0apafc0adl3933egmpll4br17850b.apps.googleusercontent.com',

        'client_secret' => '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',

        'redirect' => 'http://localhost:8000/auth/google/callback',



    ],

 	'firebase' => [
        'server_api_key' => env('FIREBASE_SERVER_API_KEY','AAAAciCYSdc:APA91bEq8Nb4ykFoQI6_eu1At70wdak_EJHFxNWDLZTMH6MzTiPrYcsMdEPnvq4I43DWLft6sWc2nGokZuCaE98h9rUmH4_jno0NnHIJw0Tah8c8wBDq7fp_HnMHFK2-aC-Z6qjFYAiK')
    ],


    "apple" => [
        "client_id" => "com.JoyBox.ClassKits",
        "client_secret" => "eyJraWQiOiItLS0tLUJFR0lOIFBSSVZBVEUgS0VZLS0tLS1cbiAgICAgICAgICBNSUdUQWdFQU1CTUdCeXFHU000OUFnRUdDQ3FHU000OUF3RUhCSGt3ZHdJQkFRUWcxanVVeGlUakxtTUVsZmZsXG4gICAgICAgICAgUnJIZkQxUkNzeUZiMTNhTG01Myt4R29DYTBxZ0NnWUlLb1pJemowREFRZWhSQU5DQUFRWE1rRVA5Zm0ydlJwSVxuICAgICAgICAgIDJYRkZxSDZ3eWhVcWFMWkpXaVVVWHhTK3Z0eEdacnRtVi81U1dwQ2oxK1JVL2xwV3kxWE1TVkxwVEJCcFVGWFRcbiAgICAgICAgICBtTWE1MjJHUFxuICAgICAgICAgIC0tLS0tRU5EIFBSSVZBVEUgS0VZLS0tLS0iLCJhbGciOiJFUzI1NiJ9.eyJpc3MiOiJWUThOU0JSWFkzIiwiaWF0IjoxNjU1MjAyMDgxLCJleHAiOjE2NzA3NTQwODEsImF1ZCI6Imh0dHBzOi8vYXBwbGVpZC5hcHBsZS5jb20iLCJzdWIiOiJjb20uSm95Qm94LkNsYXNzS2l0cyJ9.xtsOv7kmakkcpH3f5I6iiJUPyOXjrdBZ4C-gRf2Yw6mGm4PNbFatQq96OLej3-GbttM74JSAZZ3XTdIfz367Tg",
    	"redirect" => "http://localhost:8000/auth/apple/callback"
    ],



];

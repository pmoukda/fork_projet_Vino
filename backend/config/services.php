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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'saq' => [
    'graphql_url' => env('SAQ_GRAPHQL_URL'),
    'api_key' => env('SAQ_API_KEY'),
    'env_id' => env('SAQ_ENV_ID'),
    'store_code' => env('SAQ_STORE_CODE', 'main_website_store'),
    'store_view' => env('SAQ_STORE_VIEW', 'fr'),
    'website_code' => env('SAQ_WEBSITE_CODE', 'base'),
],

];

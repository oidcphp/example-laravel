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

    'office365' => [
        'tenant_id' => env('OFFICE365_TENANT_ID'),
        'client_id' => env('OFFICE365_CLIENT_ID'),
        'client_secret' => env('OFFICE365_CLIENT_SECRET'),
        'redirect_uri' => env('OFFICE365_REDIRECT_URI'),
    ],

    'line' => [
        'client_id' => env('LINE_CLIENT_ID'),
        'client_secret' => env('LINE_CLIENT_SECRET'),
        'redirect_uri' => env('LINE_REDIRECT_URI'),
    ],

    'sign_in_with_apple' => [
        'client_id' => env('SIGN_IN_WITH_APPLE_CLIENT_ID'),
        'key_id' => env('SIGN_IN_WITH_APPLE_KEY_ID'),
        'private_key' => env('SIGN_IN_WITH_APPLE_PRIVATE_KEY'),
        'redirect_uri' => env('SIGN_IN_WITH_APPLE_REDIRECT_URI'),
        'team_id' => env('SIGN_IN_WITH_APPLE_TEAM_ID'),
    ],
];

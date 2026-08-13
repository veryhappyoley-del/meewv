<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'portone' => [
        'store_id' => env('PORTONE_STORE_ID'),
        'api_secret' => env('PORTONE_API_SECRET'),
        'channel_key_kakaopay' => env('PORTONE_CHANNEL_KEY_KAKAOPAY'),
        'channel_key_naverpay' => env('PORTONE_CHANNEL_KEY_NAVERPAY'),
        'channel_key_tosspay' => env('PORTONE_CHANNEL_KEY_TOSSPAY'),
    ],

    'solapi' => [
        'api_key' => env('SOLAPI_API_KEY'),
        'api_secret' => env('SOLAPI_API_SECRET'),
        'sender_number' => env('SOLAPI_SENDER_NUMBER'),
        'kakao_pf_id' => env('SOLAPI_KAKAO_PF_ID'),
        'template_verification' => env('SOLAPI_TEMPLATE_VERIFICATION'),
    ],

    'testing' => [
        'bypass_code' => env('TESTING_BYPASS_CODE'),
    ],

];

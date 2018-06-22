<?php

return [
    // 'api' => 'https://.../api',

    'facebook' => [
        'app_id' => _env('FACEBOOK_ID'),
        'app_secret' => _env('FACEBOOK_SECRET'),
        'redirect' => _env('FACEBOOK_REDIRECT', false)
    ],

    // 'graphql' => 'http://.../graphql'
];

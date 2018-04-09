<?php

return [
    'name' => env('APP_NAME', 'Aida'),
    'domain' => env('APP_DOMAIN'),
    'views' => base_path() . '/views',
    'cache' => base_path() . '/assets/cache',
    'locales' => ['en', 'es'],
    'locale' => env('LOCALE','en'),
    'middleware' => false,
    'debug' => env('APP_DEBUG', true),    
];
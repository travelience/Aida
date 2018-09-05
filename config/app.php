<?php

return [
    'name' => _env('APP_NAME', 'Aida'),
    'domain' => _env('APP_DOMAIN'),
    'views' => base_path() . '/views',
    'cache' => base_path() . '/assets/cache',
    'locales' => [],// ['en', 'es']
    'locale' => _env('LOCALE', 'en'),
    'middleware' => false,
    'debug' => _env('DEBUG', true),
];

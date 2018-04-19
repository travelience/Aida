<?php

return [
  'driver' => env('DB_DRIVER', 'mysql'), 
  'host' => env('DB_HOST', 'host'),
  'port' => env('DB_PORT', 3306),
  'database' => env('DB_DATABASE'),
  'username' => env('DB_USERNAME'),
  'password' => env('DB_PASSWORD'),
  'charset' => env('DB_CHARSET', 'utf8'),
  'collation' => env('DB_COLLOCATION', 'utf8_unicode_ci'),
  'prefix' => env('DB_PREFIX', '')
];
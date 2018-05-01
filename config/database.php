<?php

return [
  'driver' => _env('DB_DRIVER', 'mysql'),
  'host' => _env('DB_HOST', 'host'),
  'port' => _env('DB_PORT', 3306),
  'database' => _env('DB_DATABASE'),
  'username' => _env('DB_USERNAME'),
  'password' => _env('DB_PASSWORD'),
  'charset' => _env('DB_CHARSET', 'utf8'),
  'collation' => _env('DB_COLLOCATION', 'utf8_unicode_ci'),
  'prefix' => _env('DB_PREFIX', '')
];

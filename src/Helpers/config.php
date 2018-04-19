<?php

if (! function_exists('config')) {
    function config($path, $default=false)
    {
        $config = array_get( $GLOBALS['config'], $path );

        if( $config )
        {
            return $config;
        }

        return $default;
    }
}

if (! function_exists('_env')) {
    function _env($key, $default=false)
    {
        if (isset($GLOBALS['ENV'][$key])) {
            return $GLOBALS['ENV'][$key];
        }

        return $default;
    }
}
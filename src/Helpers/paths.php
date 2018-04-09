<?php


if (! function_exists('public_path')) {
    
    function public_path( $path='' )
    {
        return PUBLIC_PATH . $path;
    }

}

if (! function_exists('base_path')) {
    
    function base_path( $path='' )
    {
        return ROOT_PATH . $path;
    }

}


if (! function_exists('framework_path')) {
    
    function framework_path( $path='' )
    {
        return FRAMEWORK_PATH . $path;
    }

}

if (! function_exists('config_path')) {
    
    function config_path( $path='' )
    {
        return CONFIG_PATH . $path;
    }

}

if (! function_exists('locales_path')) {
    
    function locales_path( $path='' )
    {
        return LOCALES_PATH . $path;
    }

}
<?php

/**
* Error handler, passes flow over the exception logger with new ErrorException.
*/
function log_error( $num, $str, $file, $line, $context = null )
{
    $error = error_get_last();
    if ( $error["type"] == E_ERROR )
    {
        app()->log->error( $str );
        log_exception( new ErrorException( $str, 0, $num, $file, $line ) );
    }
}

/**
* Uncaught exception handler.
*/
function log_exception( $e )
{

    if( config('app.debug') === 'false' )
    {
        redirect('/');
    }

    if( file_exists( CONFIG_PATH .'/errors.php') )
    {
        require_once CONFIG_PATH . '/errors.php';
        exit();
    }

    include FRAMEWORK_PATH . '/views/error-log.blade.php';
    
    exit();
}

/**
* Checks for a fatal error, work around for set_error_handler not working on fatal errors.
*/
function check_for_fatal()
{
    $error = error_get_last();
    if ( $error["type"] == E_ERROR )
    {
        log_error( $error["type"], $error["message"], $error["file"], $error["line"] );
    }
}


register_shutdown_function( "check_for_fatal" );
set_error_handler( "log_error" );
set_exception_handler( "log_exception" );
ini_set( "display_errors", "off" );
error_reporting( E_ALL );
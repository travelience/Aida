<?php 

namespace Travelience\Aida\Session;

class Session {
    
    public static function flush()
    {
        $_SESSION = null;
        unset( $_SESSION );
        session_destroy();
    }

    public static function forget( $key )
    {
        $_SESSION[$key] = null;
        unset( $_SESSION[$key] );
    }

    public static function get( $key, $default=false )
    {
        return array_get($_SESSION, $key, $default);
    }

    public static function has( $key )
    {

        if( array_get($_SESSION, $key) )
        {
            return true;
        }

        return false;
    }

    public static function put( $key, $value )
    {
        array_set($_SESSION, $key, $value);
        return $value;
    }

    public static function set( $key, $value )
    {
        array_set($_SESSION, $key, $value);
        return $value;
    }
}
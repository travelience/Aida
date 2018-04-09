<?php 

namespace Travelience\Aida\Session;

class Session {
    
    public static function flush()
    {
        $_SESSION = null;
        unset( $_SESSION );
    }

    public static function forget( $key )
    {
        $_SESSION[$key] = null;
        unset( $_SESSION[$key] );
    }

    public static function get( $key, $default=false )
    {
        if( isset($_SESSION[$key]) )
        {
            return $_SESSION[$key];
        }

        return $default;
    }

    public static function has( $key )
    {
        if( isset($_SESSION[$key]) )
        {
            return true;
        }

        return false;
    }

    public static function put( $key, $value )
    {
        return $_SESSION[$key] = $value;
    }

    public static function set( $key, $value )
    {
        return $_SESSION[$key] = $value;
    }
}
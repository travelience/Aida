<?php 

namespace Travelience\Aida\Cookie;

class Cookie {
    
    public static function flush()
    {
        if( count($_COOKIE) )
        {
            foreach( $_COOKIE as $key=>$value )
            {
                Cookie::forget($key);
            }
        }
    }

    public static function forget( $key )
    {
        setcookie($key, null, -1, '/');
    }

    public static function get( $key, $default=false )
    {
        if( isset($_COOKIE[$key]) )
        {
            return unserialize($_COOKIE[$key]);
        }

        return $default;
    }

    public static function has( $key )
    {
        if( isset($_COOKIE[$key]) )
        {
            return true;
        }

        return false;
    }

    public static function put( $key, $value, $duration=60 )
    {
        if( is_array($value) ||  is_object($value) )
        {
            $value = serialize($value);
        }

        setcookie($key, $value, time() + $duration, '/');
        return $value;
    }

    public static function set( $key, $value, $duration=60 )
    {
        self::put($key, $value, $duration);
    }
}
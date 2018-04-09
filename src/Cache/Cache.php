<?php 

namespace Travelience\Aida\Cache;

use Travelience\Aida\Session\Session;

class Cache {
    
    public $path = false;

    public function __construct()
    {
        $this->path = ASSETS_PATH . '/cache';

    }

    public function flush()
    {
        $files = glob($this->path . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function forget( $key )
    {
       @unlink( $this->getFilePath($key) );
    }

    public function get( $key, $callback=false, $duration=60 )
    {

        // return if we have
        if( $this->isValid($key, $duration) )
        {
            $r = @file_get_contents( $this->getFilePath($key) );
            return json_decode($r);
        }

        if( $callback )
        {
            if( is_object( $callback ) )
            {
                $data = $callback( app()->req, app()->res );
                $this->put($key, $data);   
            }
            else
            {
                $this->put($key, $callback);
            }

            return $this->get($key);
        }   

        return false;
    }

    public function isValid( $key, $duration )
    {

        if( !$this->has($key) )
        {
            return false;
        }
        
        $start  = date_create( date('Y-m-d H:i:s', @filectime( $this->getFilePath($key) )) );
        $end 	= date_create( date('Y-m-d H:i:s', time() ) );
        $diff  	= date_diff( $start, $end );

        if( $diff->days >= $duration )
        {
          return false;
        }

        return true;
    }

    public function has( $key )
    {
        return file_exists( $this->getFilePath($key) );
    }

    public function put( $key, $data )
    {
        file_put_contents( $this->getFilePath($key) , json_encode( $data )  );
    }

    public function set( $key, $data )
    {
        $this->put($key, $data);
    }

    public function getFilePath( $key )
    {
        $base = '';

        if( $lang = Session::get('lang') )
        {
            $base = $lang .'_';
        }

        return $this->path . '/' . $base . $key . '.json';
    }

   
}
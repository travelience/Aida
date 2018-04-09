<?php

namespace Travelience\Aida\Core;

class Env {

    public function handle( $req, $res )
    {
    
        if( !file_exists( ROOT_PATH .'/.env') )
        {
            return false;
        }
        
        $env = file_get_contents(ROOT_PATH .'/.env');

        if( strlen($env) < 10 )
        {
            return false;
        }

        $env = nl2br($env);
        $env = explode('<br />', $env);

        $env = array_filter( $env, function($item){
            if( strlen($item) > 0 && str_contains($item, '=') )
            {
                return true;
            }

            return false;
        });

        $env = array_map( function( $item ){
            $item = explode('=', $item );
            return [trim($item[0]) => trim($item[1]) ];
        }, $env );

        $config = [];

        foreach( $env as $key=>$val)
        {
            $config[ key($val) ] = $val[ key($val) ];
        }
        
        $GLOBALS['ENV'] = $config;
    }

}
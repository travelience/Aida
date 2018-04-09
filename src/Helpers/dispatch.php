<?php

if (! function_exists('dispatch')) {
    function dispatch( $method, $payload=[] )
    {
       $app = app();

       if( function_exists($method) )
       {
          return $method( $app->req, $app->res, $payload );
       }

       return false;
    }
}
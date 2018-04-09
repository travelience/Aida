<?php

if (! function_exists('is_page')) {
    function is_page($page)
    {
        $page = explode('?', $page);
        if (current_url_path() == $page[0]) {
            return true;
        }

        return false;
    }
}

if (! function_exists('is_page_path')) {
    function is_page_path($page)
    {
        $page = explode('?', $page);
        if (str_contains(current_url_path(), $page[0])) {
            return true;
        }

        return false;
    }
}


if (! function_exists('reload')) {
    function reload()
    {
        header("HTTP/1.1 302");
        header("Location: " . current_url_path());
        die();
    }
}

if (! function_exists('current_domain')) {
    function current_domain()
    {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://". $_SERVER['HTTP_HOST'];
    }
}

if (! function_exists('current_url')) {
    function current_url($params=false)
    {
        $request_uri = $_SERVER['REQUEST_URI'];

        if (!$params) {
            $request_uri = explode('?', $_SERVER['REQUEST_URI']);
            $request_uri = $request_uri[0];
        }

        return current_domain() . $request_uri;
    }
}


if (! function_exists('current_url_path')) {
    function current_url_path($params=false)
    {
        $request_uri = $_SERVER['REQUEST_URI'];

        if (!$params) {
            $request_uri = explode('?', $_SERVER['REQUEST_URI']);
            $request_uri = $request_uri[0];
        }

        return $request_uri;
    }
}

if (! function_exists('is_route')) {

    /**
    * Check if you are inside of one spesific route
    * mostly used to active a menu inside template
    *
    * @param String $name
    * @return boolean
    */
    function is_route( $name )
    {
        if( current_route() == $name)
        {
            return true;
        }

        return false;
    }

}

if (! function_exists('is_route_path')) {

    /**
    * Determines if the given string contains the given value inside the current route
    * mostly used to active a menu inside template
    *
    * @param String $name
    * @return boolean
    */
    function is_route_path( $name )
    {
        if(preg_match('/('. $name .')/i' , current_route() , $m) === 1)
        {
            return true;
        }

        return false;
    }

}

if (! function_exists('current_route')) {

    /**
    * Return the current route name, shortcut
    *
    * @return boolean
    */
    function current_route()
    {
        if( isset($GLOBALS['route']) )
        {
            return $GLOBALS['route']['name'];
        }

        return '';        
    }

}

if (! function_exists('localization_url')) {
    function localization_url($locale, $url=false)
    {
        return \Travelience\Aida\Router\Localization::getLocalizedURL($locale, $url);
    }
}

function route($name, $params=[])
{
   $router = Travelience\Aida\Router\Router::getInstance();
   return $router->get($name, $params);
}
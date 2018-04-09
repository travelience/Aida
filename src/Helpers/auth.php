<?php

if (! function_exists('auth')) {
    function auth()
    {
        $auth = new Travelience\Aida\Auth\Auth();
        return $auth;
    }
}

if (! function_exists('isAuth')) {
    function isAuth()
    {
        $auth = new Travelience\Aida\Auth\Auth();
        return $auth->check();
    }
}

if (! function_exists('logout')) {
    function logout()
    {
        $auth = new Travelience\Aida\Auth\Auth();
        $auth->logout();
    }
}

if (! function_exists('user')) {
    function user()
    {
        $auth = new Travelience\Aida\Auth\Auth();
        return $auth->user();
    }
}

if (! function_exists('setToken')) {
    function setToken($token)
    {
        $auth = new Travelience\Aida\Auth\Auth();
        $auth->setToken( $token );
    }
}
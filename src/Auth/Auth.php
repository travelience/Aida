<?php

namespace Travelience\Aida\Auth;

use Travelience\Aida\Cookie\Cookie;
use Travelience\Aida\Core\Maybe;

class Auth
{
    public $key = 'auth';

    public function check()
    {
        return Cookie::has( $this->key );
    }

    public function user()
    {
        return new Maybe( Cookie::get( $this->key ) );
    }

    public function authenticate( $data )
    {
        return Cookie::put('auth', $data, (10 * 365 * 24 * 60 * 60));
    }

    public function logout()
    {
        return Cookie::forget('auth');
    }

    public function setToken( $token )
    {
        $user = $this->user();
        $user->token = $token;

        $this->authenticate( $user );

    }
}
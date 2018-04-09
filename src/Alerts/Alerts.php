<?php

namespace Travelience\Aida\Alerts;

use Travelience\Aida\Session\Session;

class Alerts
{
    public $key = 'alerts';

    public function show( $text, $type='success' )
    {
        $data = [
            'message' => $text,
            'type' => $type
        ];

        Session::put( $this->key , $data);
    }

    public function flush()
    {
        Session::forget( $this->key );
    }

    public function has()
    {
        return Session::get( $this->key );
    }

    public function get()
    {
        return Session::get( $this->key );
    }
}
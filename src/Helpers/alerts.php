<?php

if (! function_exists('alert')) {
    function alert($text, $type='success')
    {
        $alert = new Travelience\Aida\Alerts\Alerts();
        $alert->show($text, $type);
    }
}

if (! function_exists('hasAlert')) {
    function hasAlert()
    {
        $alert = new Travelience\Aida\Alerts\Alerts();
        return $alert->get();
    }
}

if (! function_exists('alert_flush')) {
    function alert_flush()
    {
        $alert = new Travelience\Aida\Alerts\Alerts();
        $alert->flush();
    }
}
<?php

namespace Travelience\Aida\Core;
use Travelience\Aida\Router\Localization;

class Translator {

    public function handle( $req, $res )
    {
        $locales = config('app.locales');
        
        if( !$locales )
        {
            return false;
        }

        $lang = Localization::getLocale();
        $path = LOCALES_PATH . '/' . $lang . '.php';

        if( !file_exists( $path ) )
        {
            return false;
        }

        $GLOBALS['trans'] = include $path;
    }

}
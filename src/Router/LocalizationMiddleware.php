<?php

namespace Travelience\Aida\Router;

use Travelience\Aida\Router\Localization;

class LocalizationMiddleware
{
    public function handle($req, $res)
    {        
        
        if(  !config('app.locales') )
        {
            session('lang', config('app.locale'));
            return false;
        }

        $segment = Localization::getFirstSegment();
        $locale = Localization::getLocale();

        if( $segment != $locale )
        {
            session('lang', $locale);
            redirect( Localization::getLocalizedURL($locale), 302 );
        }

        session('lang', $locale);
        
    }
}
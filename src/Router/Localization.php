<?php

namespace Travelience\Aida\Router;

use Travelience\Aida\Router\Router;

class Localization
{
    public static function getLocale()
    {
        $locales = config('app.locales');
        $lang = self::getFirstSegment();

        if (!$locales || count($locales) == 0) {
            return config('app.locale');
        }

        if (in_array($lang, $locales)) {
            return $lang;
        }

        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        
        // chinese fixes
        if ($lang == 'zh-cn') {
            $lang = 'cn';
        }
        
        if ($lang == 'zh-tw') {
            $lang = 'tw';
        }

        // check browser language
        if (isset($lang)) {
            $lang = substr($lang, 0, 2);

            if (in_array($lang, $locales)) {
                return $lang;
            }
        }

        return config('app.locale');
    }


    public static function getLocalizedURL($locale, $url=false)
    {
        $segment = self::getFirstSegment();
        $current_locale = false;
        $url = $url ? $url : current_url_path();

        if (in_array($segment, config('app.locales'))) {
            $current_locale = $segment;
        }

        // is current locale
        if ($current_locale == $locale) {
            return $url;
        }

        // top page
        if ($_SERVER['REQUEST_URI'] == '/') {
            return $_SERVER['REQUEST_URI'] . $locale;
        }

        // url without locale
        if (!$current_locale) {
            return '/' . $locale . $url;
        }

        // replace locale
        return str_replace('/'. $current_locale, '/'.$locale, $url);
    }

    public static function getFirstSegment()
    {
        $router = Router::getInstance();
        return $router->getSegments()[1];
    }
}

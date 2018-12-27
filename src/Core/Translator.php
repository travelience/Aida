<?php

namespace Travelience\Aida\Core;

use Travelience\Aida\Router\Localization;

class Translator
{
    public function handle($req, $res)
    {
        $lang = Localization::getLocale();

        $translations = $this->getTranslations($lang);

        if (!$translations) {
            return false;
        }

        $GLOBALS['trans'] = $translations;
    }

    public function getTranslations($lang)
    {
        $path = LOCALES_PATH . '/' . $lang . '.php';

        if (!file_exists($path)) {
            return false;
        }

        $translations = include $path;

        return $translations;
    }
}

<?php

namespace Travelience\Aida\Core;

class Config
{
    public $config = ['app', 'mail', 'seo' ,'services', 'database'];

    public function handle($req, $res)
    {
                
        // config defaults
        foreach ($this->config as $file) {
            $path = FRAMEWORK_PATH . '/config/' . $file . '.php';
            $this->load($file, $path);
        }

        // configs merge
        foreach ($this->config  as $file) {
            $path = CONFIG_PATH . '/' . $file . '.php';
            $this->load($file, $path);
        }
    }

    public function load($key, $path)
    {
        if (!file_exists($path)) {
            return false;
        }

        $config = include $path;

        if (isset($GLOBALS['config'][ $key ])) {
            return $GLOBALS['config'][ $key ] = array_merge($GLOBALS['config'][ $key ], $config);
        }

        return $GLOBALS['config'][ $key ] = $config;
    }
}

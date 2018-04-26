<?php

namespace Travelience\Aida\Core;

class Includes
{
    public $includes = ['routes', 'middlewares', 'plugins', 'actions', 'helpers'];
    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function handle($req, $res)
    {
        $app = $this->app;
        
        // includes
        foreach ($this->includes as $file) {
            $path = CONFIG_PATH . '/'. $file;
            $fileName = $path . '.php';

            if(is_dir($path)) {
              require_all($path);
            }

            if (file_exists($fileName)) {
                require $fileName;
            }
        }
    }
}
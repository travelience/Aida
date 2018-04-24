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
            $path = CONFIG_PATH . '/'. $file . '.php';

            if (file_exists($path)) {
                require $path;
            }
        }
    }
}

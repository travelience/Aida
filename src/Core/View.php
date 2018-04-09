<?php

namespace Travelience\Aida\Core;
use Travelience\Aida\Blade\Blade;

class View {

    public function handle( $req, $res )
    {
        
        $path_views = VIEWS_PATH;
        $path_cache = ASSETS_PATH . '/views';

        $blade = new Blade( $path_views, $path_cache );
        $blade->view()->addNamespace('Aida', FRAMEWORK_PATH . '/views' );

        // register
        app()->set('blade', $blade);
        app()->set('template', $blade->view());        

    }

}
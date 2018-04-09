<?php
namespace Travelience\Aida;

use Travelience\Aida\Core\Singleton;
use Travelience\Aida\Core\Plugins;
use Travelience\Aida\Core\Config;
use Travelience\Aida\Core\Env;

class Application extends Singleton
{
    use Plugins;

    public $context = [];
    public $events = [];

    public function __construct()
    {

        session_start();

        define('FRAMEWORK_PATH', str_replace('/src', '', __DIR__) );
        define('ROOT_PATH', str_replace('/public', '', getcwd()) );
        define('CONFIG_PATH', ROOT_PATH .'/config');
        define('VIEWS_PATH', ROOT_PATH . '/views');
        define('ASSETS_PATH', ROOT_PATH.'/assets');
        define('LOCALES_PATH', CONFIG_PATH.'/locales');
        define('PAGES_FOLDER', 'pages');

        $this->withHelpers();
        $this->withConfig();
        $this->runEvents('config');

    }
    
    public function set( $key, $object ) 
    {
        $this->context[ $key ] = $object;
    }

    public function use( $callback ) 
    {
        $this->on('before', $callback);
    }

    /*
        init | after | before | error
    */
    public function on( $type, $callback ) 
    {
        $this->events[$type][] = $callback;
    }

    public function get( $path, $config=[], $callback=null )
    {
        $this->route( $path, 'GET', $config, $callback );
    }

    public function any( $path, $config=[], $callback=null )
    {
        $this->route( $path, 'ANY', $config, $callback );
    }

    public function post( $path, $config, $callback=null )
    {
        $this->route( $path, 'POST', $config, $callback );
    }

    public function route( $path, $method, $config, $callback )
    {
        $config['callback'] = $callback;
        $config['method'] = $method;
        $name = $config['name'] ?? str_slug( $method .'_'. $path );
        $this->router->register( $name, $path, $config );
    }


    public function render( $callback=false )
    {
        
        $route = $this->req->getCurrentRoute();

        if( isset($route['middlewares']) )
        {
            $this->router->runMiddlewares( $route['middlewares'], $this );
        }

        $this->runEvents('before');   

        if( is_callable($route['callback']) )
        {
            return $route['callback']( $this->req, $this->res );
        }

        echo $this->res->render( $route['page'] );

        $this->runEvents('after');
    }


    public function run( $callback=false )
    { 
        
        $this->withEssentials();
        $this->runEvents('init');

        try {

            if( $callback )
            {
                return $callback( $this->req, $this );
            }

            return $this->render();

        }catch( \Exception $e )
        {

            $res->log->error( $e->getMessage() );

            $this->runEvents('error');
        }
        
    }

    public function __get($name) 
    {
        if (isset($this->context[$name])) {
            return $this->context[$name];
        }

        return null;
    }

    public function runEvents( $type )
    {
        if( !isset($this->events[ $type ]) )
        {
            return false;
        }

        $events = $this->events[ $type ];

        if( !$events )
        {
            return false;
        }

        foreach( $events as $middleware )
        {
            if (is_callable($middleware)) {
                $middleware($this->req, $this->res);
                continue;
            }
            
            if( is_object($middleware) ){
                $middleware->handle( $this->req, $this->res);
                continue;
            }
        }
        
    }

}
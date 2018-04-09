<?php 

namespace Travelience\Aida\Request;

use Illuminate\Http\Request as BaseRequest;
use Travelience\Aida\Request\Validator;
use Travelience\Aida\Request\Errors;
use Travelience\Aida\Session\Session;

class Request extends BaseRequest
{
    use Validator;
    use Errors;
    
    public $params = [];

    public function handle( $req, $res )
    {

        $app = app();

        $app->on('before', function($req, $res){
            $this->flash();
        });

        $app->on('after', function($req, $res){
            Session::forget('_input_');
        });

    }

    public function onSubmit()
    {
        return  ( $this->method() == 'POST' );
    }

    public function setParams( $params )
    {
        $this->params = $params;
    }

    public function params()
    {
        return $this->params;
    }

    public function old( $key = null, $default = null )
    {
        
        $input = Session::get('_input_') ??  []; 

        $value = array_get($input, $key);

        if( $value )
        {
            return $value;
        }

        return $default;
    }

    public function getCurrentRoute()
    {
        $app = app();
 
        // match routes
        $route = $GLOBALS['route'] = $app->router->match();
        $app->context['page'] = $route['page'];
        $app->context['req']->setParams( $route['params'] );
 
        return $route;
    }

    public function getCurrentRouteName()
    {
        return current_route();
    }

    public function flash( $data=null, $keys=[] )
    {
        $app = app();

        if( $app->req->onSubmit() )
        {
            $input = $data ?? $app->req->all();
            Session::put('_input_', $input);
        }
       
    }

}
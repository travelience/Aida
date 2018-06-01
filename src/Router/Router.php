<?php

namespace Travelience\Aida\Router;

use Travelience\Aida\Core\Singleton;

class Router extends Singleton
{
    private $routes = [];
    private $middlewares = [];
    
    public function register($name, $path, $config=[])
    {
        if (substr($path, 0, 1) != '/') {
            $path = '/' . $path;
        }

        $priority = count(explode('/', $path));
        $priority += count(explode(':', $path));

        if (!str_contains($path, ':')) {
            $priority +=50;
        }

        if ($config['method'] == 'POST') {
            $priority += 100;
        }
        
        $this->routes[ $name ] = [
            'name' => strtolower($name),
            'path' => $path,
            'method' => $config['method'],
            'config' => $this->parsePath($path),
            'page' => PAGES_FOLDER . '/' . ($config['page'] ?? $name),
            'callback' => $config['callback'] ?? null,
            'middlewares' => $config['middlewares'] ?? null,
            'priority' => $priority
        ];

        $GLOBALS['routes'] = $this->routes;
    }

    public function middleware($name, $callback)
    {
        $this->middlewares[ $name ] = $callback;
    }

    public function runMiddlewares($middlewares, $app)
    {
        foreach ($middlewares as $key) {
            if ($this->middlewares[ $key ]) {
                $this->middlewares[ $key ]($app->req, $app->res);
            }
        }
    }

    public function parsePath($path)
    {
        $segments = explode('/', $path);
        $params = [];

        foreach ($segments as $item) {
            if (substr($item, 0, 1) == ':') {
                $params[] = substr($item, 1);
                $path = str_replace($item, '(.*)', $path);
            }
        }

        return [
            'pattern' => "/^".str_replace('/', '\/', $path)."$/",
            'params' => $params
        ];
    }

    public function getSegments($path=false)
    {
        if (!$path) {
            $path = $this->getRequestUri();
        }

        $path = explode('/', $path);
        unset($path[0]);
        
        return $path;
    }

    public function getParams($route, $matches)
    {
        if (!isset($route['config']['params'])) {
            return [];
        }
  
        $params = [];
  
        foreach ($route['config']['params'] as $index=>$key) {
            $params[$key] = $matches[ $index+1 ];
        }
  
        return $params;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getRequestUri()
    {
        $path = $_SERVER['REQUEST_URI'];
        $path = explode('?', $path);
            
        return $path[0];
    }

    public function getForcedRoute()
    {
        $path = $this->getRequestUri();
        $name = substr($path, 1);

        if ($name == '') {
            $name = 'index';
        }

        return [
            'name' => $name,
            'page' => $name,
            'path' => $path,
            'params' => [],
            'segments' => $this->getSegments($path),
            'callback' => false
        ];
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function match($path=false)
    {
        if (!$path) {
            $path = $this->getRequestUri();
        }

        if (config('app.locales')) {
            $path = str_replace('/'. session('lang'), '', $path);
        }

        if ($path == '') {
            $path = '/';
        }

        $original_path = $path;

        // sort by priority

        $routes = $this->routes;
        usort($routes, function ($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });

        foreach ($routes as $route) {
            if ($route['method'] != $this->getMethod() && $route['method'] != 'ANY') {
                continue;
            }

            preg_match($route['config']['pattern'], $path, $matches);

            if ($matches) {
                unset($matches[0]);
                $route['matches'] = $matches;
                $route['params'] = $this->getParams($route, $matches);
                $route['segments'] = $this->getSegments($original_path);
                return $route;
            }
        }

        return $this->getForcedRoute();
    }


    public function get($name, $params=[])
    {
        $name = strtolower($name);
        $base = '/';

        if (config('app.locales')) {
            $base .= session('lang').'/';
        }

        if (!isset($GLOBALS['routes'][$name])) {
            return $base . str_replace('.', '/', $name);
        }

        $route = $GLOBALS['routes'][$name];
        $query = '';
        
        if ($params) {
            if (!is_array($params)) {
                $value = $params;
                $params = [];
                $params[ $route['config']['params'][0] ] = $value;
            }

            $route_params = str_get_params($route['path'], $params);


            $get_params = array_except($params, array_keys($route_params));
    
            if (count($get_params) > 0) {
                $query = '?'.http_build_query($get_params);
            }
        }

        $path = $base . str_params($route['path'], $params) . $query;
        $path = str_replace('//', '/', $path);

        if ($path != '/' && substr($path, -1) == '/') {
            $path =  substr($path, 0, -1);
        }
       
        return $path;
    }
    
    public function getPaths()
    {
        if (!isset($GLOBALS['routes'])) {
            return [];
        }

        $routes = [];

        foreach ($GLOBALS['routes'] as $route) {
            $routes[] = [
             'name' => $route['name'],
             'path' =>   $route['path'],
            //  'priority' => $route['priority'],
            //  'config' => $route['config'],
            ];
        }

        return $routes;
    }
}

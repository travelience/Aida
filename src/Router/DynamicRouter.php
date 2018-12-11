<?php
namespace Travelience\Aida\Router;

class DynamicRouter
{
    public $path;
    public $routes = false;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function handle($req, $res)
    {
        $routes = $this->findRoutes();

        if (!$routes) {
            return false;
        }

        foreach ($routes as $route) {
            $config = [
                'name' => $route['name'],
                'page' => $route['page']
            ];
            
            
            app()->any($route['path'], $config);
        }
    }

    public function findRoutes()
    {
        if (!file_exists($this->path)) {
            return false;
        }

        $files = $this->listFilesInFolders($this->path);
        
        if (!is_array($files)) {
            return false;
        }

        $files = array_flatten($files);

        foreach ($files as $file) {
            $path = str_replace([ $this->path, '.blade.php'], '', $file);
            $pattern = str_replace('/_', '/:', $path);
            
            $name = str_replace('/', '.', $path);

            if (substr($name, 0, 1) == '.') {
                $name = substr($name, 1);
            }

            $pattern = str_replace('/index', '', $pattern);
            
            if (!str_contains($name, 'component')) {
                $page = $name;

                $name = str_replace('.index', '', $name);

                if ($name == 'index') {
                    $name = 'home';
                }

                $this->routes[] = [
                    'name' => $name,
                    'path' => $pattern,
                    'page' => $page
                ];
            }
        }

        return $this->routes;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function listFilesInFolders($dir)
    {
        $fileInfo     = scandir($dir);
        $allFileLists = [];
    
        foreach ($fileInfo as $folder) {
            if (substr($folder, 0, 1) !== '.') {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $folder) === true) {
                    $allFileLists[$folder] = $this->listFilesInFolders($dir . DIRECTORY_SEPARATOR . $folder);
                } else {
                    $allFileLists[$folder] = $dir . DIRECTORY_SEPARATOR .$folder;
                }
            }
        }
    
        return $allFileLists;
    }
}

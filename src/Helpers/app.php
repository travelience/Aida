<?php

if (! function_exists('app')) {
    function app()
    {
        return Travelience\Aida\Application::getInstance();
    }
}

if (! function_exists('db')) {
    function db()
    {
        return Travelience\Aida\Database\Database::init();
    }
}


if (! function_exists('cookie')) {
    function cookie($key, $value=false, $duration=60)
    {
        $cookie = new \Travelience\Aida\Cookie\Cookie();

        if (!$value) {
            if ($cookie::has($key)) {
                return $cookie::get($key);
            }

            return false;
        }

        return $cookie::put($key, $value, $duration);
    }
}

if (! function_exists('session')) {
    function session($key, $value=false)
    {
        $session = new \Travelience\Aida\Session\Session();

        if (!$value) {
            if ($session::has($key)) {
                return $session::get($key);
            }

            return false;
        }

        return $session::put($key, $value);
    }
}

if (! function_exists('session_forget')) {
    function session_forget($key)
    {
        $session = new \Travelience\Aida\Session\Session();
        $session::forget($key);
    }
}

if (! function_exists('session_flush')) {
    function session_flush()
    {
        $session = new \Travelience\Aida\Session\Session();
        $session::flush();
    }
}

if (! function_exists('array_remove_null')) {
    function array_remove_null($item)
    {
        if (!is_array($item)) {
            return $item;
        }

        return collect($item)
            ->reject(function ($item) {
                return is_null($item);
            })
            ->flatMap(function ($item, $key) {
                return is_numeric($key)
                    ? [array_remove_null($item)]
                    : [$key => array_remove_null($item)];
            })
            ->toArray();
    }
}

if (! function_exists('graphql')) {
    function graphql($host, $headers=[])
    {
        return new Travelience\Aida\GraphQL\GraphQL($host, $headers);
    }
}

if (! function_exists('facebook')) {
    function facebook($config=false)
    {
        return new Travelience\Aida\Facebook\Facebook($config);
    }
}

if (! function_exists('api')) {
    function api($host, $params=[])
    {
        return new Travelience\Aida\Api\Api($host, $params);
    }
}

if (! function_exists('redirect')) {
    function redirect($to, $code='302')
    {
        header("HTTP/1.1 ". $code);
        header("Location: ". $to);
        die();
    }
}

if (! function_exists('trans')) {
    function trans($key, $params=[], $default=false)
    {
        $key = strtolower($key);
        $key = str_replace(' ', '', $key);
        
        if (!isset($GLOBALS['trans'])) {
            return (!$default ? $key : $default);
        }

        $text = array_get($GLOBALS['trans'], $key);

        if (!$text) {
            return (!$default ? $key : $default);
        }
    
        return str_params($text, $params);
    }
}

if (! function_exists('__')) {
    function __($key, $params=[], $default=false)
    {
        return trans($key, $params, $default);
    }
}

if (! function_exists('cache')) {
    function cache($key, $callback=false, $duration=60)
    {
        $cache = new \Travelience\Aida\Cache\Cache();
        return $cache->get($key, $callback, $duration);
    }
}

if (! function_exists('array_to_table')) {
    
    /**
    * Get html of a table for the given array
    *
    * @param Array $data
    * @param String $style
    * @param String $class
    * @return String
    */
    function array_to_table($data, $class='', $style='')
    {
        $html = "<table class='table {$class}' style='font-family:Arial; font-size:14px;' cellpadding='10'>";

        if ($data) {
            foreach ($data as $key=>$value) {
                $html .= "<tr >";

                $html .= "<td width='140' style='{$style}'><b>" . ucfirst($key) . "</b></td>";
                $html .= "<td style='{$style}'>{$value}</td>";

                $html .= "</tr>";
            }
        }

        $html .= "</table>";

        return $html;
    }
}

if (! function_exists('pp')) {
    function pp($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

if (! function_exists('pdd')) {
    function pdd($var)
    {
        echo "<pre>";
        dd($var);
    }
}

if (! function_exists('array_to_ul')) {

    /**
    * Get html of a ul for the given array
    *
    * @param Array $data
    * @param String $style
    * @param String $class
    * @return String
    */
    function array_to_ul($array, $class='', $style='')
    {
        if (!is_array($array)) {
            return false;
        }
        
        $html = "<ul class='{$class}' style='{$style}'>";

        foreach ($array as $key=>$value) {
            if (is_numeric($key)) {
                $html .= "<li>{$value}</li>";
            } else {
                $html .= "<li><b>{$key}:</b> {$value}</li>";
            }
        }

        $html .= "</ul>";

        return $html;
    }
}


if(! function_exists('require_all')) {
    function require_all($path) {
      foreach(new \DirectoryIterator($path) as $fileInfo) {
        if($fileInfo->isDot()) continue;
        if($fileInfo->isFile()) {
          require_once($fileInfo->getPathname());
        }
  
        if($fileInfo->isDir()) {
          require_all($fileInfo->getPathname());
        }
      }
    }
  }

  if (! function_exists('carbon')) {
      function carbon($date)
      {
          return \Carbon\Carbon::parse($date);
      }
  }

  if (! function_exists('now')) {
      function now()
      {
          return \Carbon\Carbon::now();
      }
  }
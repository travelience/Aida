<?php

if (! function_exists('str_params')) {
    function str_params($line, $replace = [])
    {
        if (count($replace) == 0) {
            return $line;
        }
        foreach ($replace as $key => $value) {
            $line = str_replace(
            [':'.$key, ':'.strtoupper($key), ':'.ucfirst($key)],
            [$value, strtoupper($value), ucfirst($value)],
            $line
        );
        }
        return $line;
    }
}

if (! function_exists('str_get_params')) {
    function str_get_params($line, $replace = [])
    {
        $params = [];
        if (count($replace) == 0) {
            return $line;
        }

        foreach ($replace as $key => $value) {
            $prev_line = $line;
        
            $line = str_replace(
            [':'.$key, ':'.strtoupper($key), ':'.ucfirst($key)],
            [$value, strtoupper($value), ucfirst($value)],
            $line
        );


            if ($prev_line != $line) {
                $params[$key] = $value;
            }
        }
        return $params;
    }
}

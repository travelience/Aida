<?php

if (! function_exists('dispatch')) {
    function dispatch($method, $payload=[], $callback = null)
    {
        $app = app();
        try {
            if (function_exists($method)) {
                $response = $method($app->req, $app->res, $payload);

                if (is_object($response)) {
                    mergeErrors($response);
                    flashErrors($response->getErrorsAsString());
                }
               
                if (!$app->req->hasErrors() && is_callable($callback)) {
                    $callback($app->req, $response);
                    return;
                }

                return $response;
            }
        } catch (\Exception $e) {
            $app->req->setErrors($e->getMessage());
            flashErrors($e->getMessage());
            return;
        }

        return false;
    }
}

if (!function_exists('mergeErrors')) {
    function mergeErrors($data)
    {
        if (method_exists($data, 'hasErrors') && method_exists($data, 'setErrors')) {
            if ($data->hasErrors()) {
                app()->req->setErrors($data->errors());
            }
        }
    }
}

if (!function_exists('flashErrors')) {
    function flashErrors($errors)
    {
        if ($errors) {
            app()->res->alert($errors, 'danger');
        }
    }
}

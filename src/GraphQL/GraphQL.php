<?php

namespace Travelience\Aida\GraphQL;

use Travelience\Aida\GraphQL\Client;
use Travelience\Aida\GraphQL\Exceptions\GraphQLInvalidResponse;
use Travelience\Aida\GraphQL\Exceptions\GraphQLMissingData;
use Travelience\Aida\GraphQL\Response;

class GraphQL
{
    public $headers = [];
    public $path = 'graphql';
    public $client = false;

    public function __construct($host, $headers=[])
    {
        $this->headers = $headers;
        $this->client = new Client($host);
    }
    
    public function headers()
    {
        $this->headers = [];
    }

    public function response($query, $variables = [], $headers = [])
    {
        $headers = array_merge($headers, $this->headers);
        $files = null;

        if (count($variables) > 0) {
            $variables = array_remove_null($variables);
        }

        if (strlen($query) < 30) {
            $file = config_path() . '/' . $this->path . '/' . $query. '.gql';

            
            if (file_exists($file)) {
                $query = file_get_contents($file);
            }
        }

        if (count($variables) > 0) {
            foreach ($variables as $key=>$value) {
                if (is_a($value, 'Illuminate\Http\UploadedFile')) {
                    $variables[$key] = true;
                    $files[$key] = $value;
                }
            }
        }

        return $this->client->response($query, $variables, $files, $headers);
    }
}

<?php

namespace Travelience\Aida\GraphQL;

use Travelience\Aida\GraphQL\Exceptions\GraphQLInvalidResponse;
use Travelience\Aida\GraphQL\Exceptions\GraphQLMissingData;

class Client
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Client constructor.
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->guzzle = new \GuzzleHttp\Client();
    }

    /**
     * Set the URL to query against
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Set an instance of guzzle to use
     *
     * @param \GuzzleHttp\Client $guzzle
     */
    public function setGuzzle(\GuzzleHttp\Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Make a GraphQL Request and get the raw guzzle response.
     *
     * @param string $query
     * @param array $variables
     * @param array $headers
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function raw($query, $variables = [], $headers = [], $files = null)
    {
        if ($files) {
            $data = [
                'multipart' => $this->formatMultiPartRequest($query, $variables, $files)
            ];
        } else {
            $data = [
                'json' => [
                    'query' => $query,
                    'variables' => $variables,
                ],
                'headers' => $headers
            ];
        }
    
        try {
            return $this->guzzle->request('POST', $this->url, $data);
        } catch (\Exception $e) {
        }
    }

    /**
     * Make a GraphQL Request and get the response body in JSON form.
     *
     * @param string $query
     * @param array $variables
     * @param array $headers
     * @param bool $assoc
     *
     * @return mixed
     *
     * @throws GraphQLInvalidResponse
     * @throws GraphQLMissingData
     */
    public function json($query, $variables = [], $files = null, $headers = [], $assoc = false)
    {
        $response = $this->raw($query, $variables, $headers, $files);

        $responseJson = json_decode($response->getBody()->getContents(), $assoc);

        if ($responseJson === null) {
            throw new GraphQLInvalidResponse('GraphQL did not provide a valid JSON response. Please make sure you are pointing at the correct URL.');
        } elseif (!isset($responseJson->data) && isset($responseJson->errors)) {
            throw new \Exception($responseJson->errors[0]->message);
        } elseif (!isset($responseJson->data)) {
            pdd($responseJson);
            throw new GraphQLMissingData('There was an error with the GraphQL response, no data key was found.');
        }

        return $responseJson;
    }

    /**
     * Make a GraphQL Request and get the guzzle response .
     *
     * @param string $query
     * @param array $variables
     * @param array $headers
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function response($query, $variables = [], $files = null, $headers = [])
    {
        $response = $this->json($query, $variables, $files, $headers);
        return new Response($response);
    }

    private function formatMultiPartRequest($query, &$variables, $files=[])
    {
        $stream = [];
        $map = [];
       
        foreach ($files as $key => $file) {
            $variables[$key] = null;
            $stream[] = [
              'name' => $key,
              'filename' => $file->getClientOriginalName(),
              'Mime-Type' => $file->getmimeType(),
              'contents' => fopen($file->getPathname(), 'r')
            ];
        }

        $data[] = [
            'name' => 'files',
            'contents' => json_encode(array_keys($files))
        ];

        $data[] = [
            'name' => 'operations',
            'contents' => json_encode([
              'query' => $query,
              'variables' => $variables
            ])
        ];
        
        return array_merge($data, $stream);
    }
}

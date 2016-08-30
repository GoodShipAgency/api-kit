<?php

namespace Mashbo\Components\ApiKit;

use Http\Client\HttpClient;
use Mashbo\Components\ApiBuilder\Standards\Http\Encoders\Exceptions\EncodingNotSupportedException;
use Mashbo\Components\ApiKit\Encoders\MultipartFormDataEncoder;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;

class ApiClient
{
    /**
     * @var HttpClient
     */
    private $client;
    private $baseUrl;

    public $headers = [];
    private $lastResponse;

    public function __construct(HttpClient $client, $baseUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    public function getAbsoluteUri($path)
    {
        return $this->baseUrl . $path;
    }

    /**
     * @return ResponseInterface
     */
    public function lastResponse()
    {
        if (is_null($this->lastResponse)) {
            throw new \LogicException("There is no last response");
        }
        return $this->lastResponse;
    }

    public function request($uri, $method, $body, $headers)
    {
        $this->lastResponse = null;

        $bodyStream = fopen('php://temp', 'w');
        fwrite($bodyStream, $body);
        rewind($bodyStream);

        $request = new Request($this->getAbsoluteUri($uri), $method, $bodyStream, $headers);

        return $this->lastResponse = $this->client->sendRequest($request);
    }

    public function get($uri, $options = [])
    {
        $headers = isset($options['headers']) ? $options['headers'] : [];
        return $this->request($uri, 'GET', null, array_merge_recursive($this->headers, $headers));
    }

    public function options($uri, $options = [])
    {
        $headers = isset($options['headers']) ? $options['headers'] : [];
        return $this->request($uri, 'OPTIONS', null, array_merge_recursive($this->headers, $headers));
    }

    public function post($uri, $data = null, $options = [])
    {
        return $this->doPostStyleRequest($uri, 'POST', $data, $options);
    }

    public function patch($uri, $data = null, $options = [])
    {
        return $this->doPostStyleRequest($uri, 'PATCH', $data, $options);
    }

    /**
     * PUT, PATCH, POST all have similar encoding semantics, but different http methods.
     *
     * This is to avoid duplication, but private in case of as-yet-unknown differences.
     *
     * @param $uri
     * @param $data
     * @param $options
     * @param $method
     * @return \Psr\Http\Message\ResponseInterface
     * @throws EncodingNotSupportedException
     */
    private function doPostStyleRequest($uri, $method, $data, $options)
    {
        $headers = isset($options['headers']) ? $options['headers'] : [];
        
        $encodedData = null;
        if (!is_null($data)) {

            switch (true) {
                case is_string($data):
                    $encodedData = $data;
                    break;
                case ($options['encoding'] == 'json'):
                    $encodedData = json_encode($data);
                    break;
                case ($options['encoding'] == 'form-data'):
                    $boundary = md5(uniqid('boundary'));
                    $encodedData = (new MultipartFormDataEncoder())->encode($data, $boundary);
                    $headers['Content-type'] = 'multipart/form-data; boundary=' . $boundary;
                    break;
                case ($options['encoding'] == 'x-www-form-urlencoded'):
                    $encodedData = http_build_query($data);
                    $headers['Content-type'] = 'application/x-www-form-urlencoded';
                    break;
                default:
                    throw new EncodingNotSupportedException($options['encoding']);
            }
        }

        return $this->request($uri, $method, $encodedData, array_merge_recursive($this->headers, $headers));
    }

    public function delete($uri, $options = [])
    {
        $headers = isset($options['headers']) ? $options['headers'] : [];
        return $this->request($uri, 'DELETE', null, array_merge_recursive($this->headers, $headers));
    }
}
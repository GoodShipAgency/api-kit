<?php

namespace Mashbo\Components\ApiKit\Assertions\Standards\JsonApi;

use Mashbo\Components\ApiKit\Assertions\Standards\Http\Response;
use Mashbo\Components\ApiKit\Assertions\Standards\Json\Json;
use Psr\Http\Message\ResponseInterface;

class JsonApi
{
    public static function assertValidResponse(ResponseInterface $response)
    {
        Response::assertContentType($response, 'application/vnd.api+json');
        Json::assertIsValid($response->getBody()->__toString());
    }

    public static function assertValidObject(ResponseInterface $response)
    {
        self::assertValidResponse($response);
        $data = $response->getBody()->__toString();
        Json::assertKeyExists($data, '[data]');
        Json::assertKeyExists($data, '[type]');
    }
    
    public static function assertDataKeyEquals(ResponseInterface $response, $key, $expected)
    {
        self::assertValidObject($response);
        
        $json = $response->getBody()->__toString();
        Json::assertIsValid($json);
        Json::assertKeyEquals($json, "[data]$key", $expected);
    }



    public static function assertDataKeyIsNotEmpty(ResponseInterface $response, $key)
    {
        self::assertValidObject($response);

        $json = $response->getBody()->__toString();
        Json::assertIsValid($json);
        Json::assertKeyNotEmpty($json, "[data]$key");
    }
}
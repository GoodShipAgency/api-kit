<?php

namespace Mashbo\Components\ApiKit\Assertions\Standards\JWT;

use Mashbo\Components\ApiKit\Assertions\Standards\Http\Response;
use Mashbo\Components\ApiKit\Assertions\Standards\Json\Json;
use Psr\Http\Message\ResponseInterface;

class JWT
{
    public static function assertValidToken(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();

        Response::assertStatusCode($response, 200);
        Response::assertContentType($response, 'application/json');
        Json::assertKeyExists($body, '[token]');
        Json::assertKeyExists($body, '[refresh_token]');
    }
}
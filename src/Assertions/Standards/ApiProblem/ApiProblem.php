<?php

namespace Mashbo\Components\ApiKit\Assertions\Standards\ApiProblem;

use Mashbo\Components\ApiKit\Assertions\Standards\Http\Response;
use Mashbo\Components\ApiKit\Assertions\Standards\Json\Json;
use Psr\Http\Message\ResponseInterface;

class ApiProblem
{
    public static function assertValid(ResponseInterface $response)
    {
        Response::assertContentType($response, 'application/problem+json');
        Json::assertIsValid($response->getBody()->__toString());
    }

    public static function assertType(ResponseInterface $response, $expected)
    {
        Json::assertKeyEquals($response->getBody()->__toString(), '[type]', $expected);
    }

    public static function assertProblemDetails(ResponseInterface $response, $expectedCode, $expectedType)
    {
        Response::assertStatusCode($response, $expectedCode);
        self::assertValid($response);
        self::assertType($response, $expectedType);
    }
}
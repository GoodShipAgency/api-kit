<?php

namespace Mashbo\Components\ApiKit\Assertions\Standards\Http;

use Mashbo\Components\ApiKit\Assertions\Exceptions\AssertionException;
use Mashbo\Components\ApiKit\Assertions\Standards\Http\Exceptions\ContentTypeAssertionException;
use Mashbo\Components\ApiKit\Assertions\Standards\Http\Exceptions\StatusCodeAssertionException;
use Psr\Http\Message\ResponseInterface;

class Response
{
    public static function assertContentType(ResponseInterface $response, $expectedContentType)
    {
        $actual = $response->getHeaderLine('Content-type');
        if ($expectedContentType !== $actual) {
            throw new ContentTypeAssertionException($expectedContentType, $actual);
        }
    }

    public static function assertStatusCode(ResponseInterface $response, $expectedCode)
    {
        $actual = $response->getStatusCode();
        if ($expectedCode !== $actual) {
            throw new StatusCodeAssertionException($expectedCode, $actual);
        }
    }

    public static function assertHeader(ResponseInterface $response, $header, $expectedValue)
    {
        if (!$response->hasHeader($header)) {
            throw new AssertionException("Expected response to have $header header");
        }

        $actualValue = $response->getHeaderLine($header);
        if ($actualValue !== $expectedValue) {
            throw new AssertionException("Expected header $header to match $expectedValue, found $actualValue");
        }
    }
}
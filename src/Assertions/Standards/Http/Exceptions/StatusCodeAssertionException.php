<?php

namespace Mashbo\Components\ApiKit\Assertions\Standards\Http\Exceptions;

use Mashbo\Components\ApiKit\Assertions\Exceptions\AssertionException;

class StatusCodeAssertionException extends AssertionException
{
    public function __construct($expected, $actual)
    {
        parent::__construct("Expected a status code of $expected but found $actual");
    }
}
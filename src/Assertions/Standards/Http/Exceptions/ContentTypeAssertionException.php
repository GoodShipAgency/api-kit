<?php

namespace Mashbo\Components\ApiKit\Assertions\Standards\Http\Exceptions;

use Mashbo\Components\ApiKit\Assertions\Exceptions\AssertionException;

class ContentTypeAssertionException extends AssertionException
{
    public function __construct($expected, $actual)
    {
        parent::__construct("Expected a content type of $expected but found $actual");
    }
}
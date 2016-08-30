<?php

namespace Mashbo\Components\ApiKit\Assertions\Standards\Json\Exceptions;

use Mashbo\Components\ApiKit\Assertions\Exceptions\AssertionException;

class InvalidJsonAssertionException extends AssertionException
{
    public function __construct($jsonErrorConstant, $jsonErrorMessage, $string)
    {
        parent::__construct("Got JSON error [$jsonErrorConstant]: $jsonErrorMessage when decoding string $string");
    }
}
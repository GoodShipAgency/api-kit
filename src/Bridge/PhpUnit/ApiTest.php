<?php

namespace Mashbo\Components\ApiKit\Bridge\PhpUnit;

use Http\Client\HttpClient;
use Mashbo\Components\ApiKit\ApiClient;
use PHPUnit_Framework_TestCase;

abstract class ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ApiClient
     */
    protected $client;

    /**
     * @return HttpClient
     */
    abstract protected function getHttpClient();

    protected $baseUri;

    public function setUp()
    {
        $this->client = new ApiClient(
            $this->getHttpClient(),
            $this->baseUri
        );
    }
}
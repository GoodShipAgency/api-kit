<?php

namespace Mashbo\Components\ApiKit\Bridge\Symfony;

use Mashbo\Components\ApiKit\Bridge\PhpUnit\ApiTest;
use Mashbo\Components\Psr7ServerRequestFactory\SimulatedServerRequestFactory;
use Mashbo\Components\SymfonyHttpClient\SymfonyHttpClientAdapter;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

abstract class KernelApiTest extends ApiTest
{
    /**
     * @var \AppKernel
     */
    protected $kernel;

    public function setUp()
    {
        $this->kernel = new \AppKernel('test', false);
        $this->kernel->boot();
        parent::setUp();
    }

    /**
     * @return SymfonyHttpClientAdapter
     */
    protected function getHttpClient()
    {
        return new SymfonyHttpClientAdapter(
            $this->kernel,
            new HttpFoundationFactory(),
            new DiactorosFactory(),
            new SimulatedServerRequestFactory()
        );
    }
}
<?php

namespace FreeElephants\JsonApiToolkit;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class AbstractHttpTestCase extends AbstractTestCase implements ResponseFactoryInterface, ServerRequestFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response($code, $headers = [], $body = null, $version = '1.1', $reasonPhrase);
    }

    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest($method, $uri, $headers = [], $body = null, $version = '1.1', $serverParams);
    }
}

<?php

namespace FreeElephants\JsonApiToolkit;

use FreeElephants\JsonApiToolkit\Psr\ErrorFactory;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use Helmich\Psr7Assert\Psr7Assertions;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AbstractHttpTestCase extends AbstractTestCase implements ResponseFactoryInterface, ServerRequestFactoryInterface
{

    use Psr7Assertions;

    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response($code, $headers = [], $body = null, $version = '1.1', $reasonPhrase);
    }

    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest($method, $uri, $headers = [], $body = null, $version = '1.1', $serverParams);
    }

    protected function createRequestHandlerWithAssertions(callable $callable): RequestHandlerInterface
    {
        return new class($callable) implements RequestHandlerInterface {
            private $callable;

            public function __construct(callable $callable)
            {
                $this->callable = $callable;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return call_user_func($this->callable, $request);
            }
        };
    }


    protected function createJsonApiResponseFactory(): JsonApiResponseFactory
    {
        return new JsonApiResponseFactory($this->createMock(EncoderInterface::class), $this, $this->createMock(ErrorFactory::class));
    }
}

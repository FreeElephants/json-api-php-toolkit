<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use FreeElephants\JsonApiToolkit\AbstractHttpTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteParamsPathMiddlewareDecoratorTest extends AbstractHttpTestCase
{
    public function testMatch()
    {
        $decorator = new RouteParamsPathMiddlewareDecorator('/v1/users/{userId}', new class() implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        });

        $request = $this->createServerRequest('GET', '/v1/users/123456');

        $handler = $this->createRequestHandlerWithAssertions(function (ServerRequestInterface $incomingRequest) use ($request) {
            $this->assertSame($request, $incomingRequest);

            return $this->createResponse();
        });

        $decorator->process($request, $handler);
    }

    public function testNotMatch()
    {
        $decorator = new RouteParamsPathMiddlewareDecorator('/v1/users/{userId}', new class() implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                throw new \RuntimeException('Process on not matched url');
            }
        });

        $request = $this->createServerRequest('GET', '/v1/users');

        $handler = $this->createRequestHandlerWithAssertions(function (ServerRequestInterface $incomingRequest) use ($request) {
            $this->assertSame($request, $incomingRequest);

            return $this->createResponse();
        });

        $decorator->process($request, $handler);
    }
}

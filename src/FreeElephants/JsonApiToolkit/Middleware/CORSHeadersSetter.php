<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CORSHeadersSetter implements MiddlewareInterface
{
    private string $accessControlAllowOriginHeaderValue;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request)
            ->withHeader('Access-Control-Allow-Origin', $this->accessControlAllowOriginHeaderValue);
    }

    public function __construct(string $accessControlAllowOriginHeaderValue = '*')
    {
        $this->accessControlAllowOriginHeaderValue = $accessControlAllowOriginHeaderValue;
    }
}

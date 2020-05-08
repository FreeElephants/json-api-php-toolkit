<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BodyParser implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request->getBody()->rewind();
        $request = $request->withParsedBody(json_decode($request->getBody()->getContents(), true));

        return $handler->handle($request);
    }
}

<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteParamsPathMiddlewareDecorator implements MiddlewareInterface
{
    private MiddlewareInterface $middleware;
    private string $path;
    private string $pattern;

    public function __construct($path, MiddlewareInterface $middleware)
    {
        $this->middleware = $middleware;
        $this->path = $path;
        $this->pattern = preg_replace('/({.*})/', '([a-zA-Z0-9_-]+)', $path);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->match($request)) {
            return $this->middleware->process($request, $handler);
        }

        return $handler->handle($request);
    }

    private function match(ServerRequestInterface $request): bool
    {
        return preg_match('#' . $this->pattern . '#', $request->getUri()->getPath()) > 0;
    }
}

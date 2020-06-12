<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteParamsPathAndMethodMiddlewareDecorator implements MiddlewareInterface
{
    private MiddlewareInterface $middleware;
    private string $path;
    private string $pattern;
    private array $methods;

    public function __construct($path, array $methods, MiddlewareInterface $middleware)
    {
        $this->middleware = $middleware;
        $this->path = $path;
        $this->pattern = preg_replace('/({.*})/', '([a-zA-Z0-9_-]+)', $path);
        $this->methods = $methods;
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
        $requestPathIsMatchWithRoute = preg_match('#' . $this->pattern . '#', $request->getUri()->getPath()) > 0;
        $requestMethodIsMatch = in_array($request->getMethod(), $this->methods);

        return $requestPathIsMatchWithRoute && $requestMethodIsMatch;
    }
}

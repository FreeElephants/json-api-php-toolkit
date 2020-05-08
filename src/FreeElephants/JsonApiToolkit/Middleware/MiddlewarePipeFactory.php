<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use FreeElephants\JsonApiToolkit\Middleware\Factory\MiddlewareFactoryInterface;
use Laminas\Stratigility\MiddlewarePipe;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewarePipeFactory
{
    private MiddlewareFactoryInterface $middlewareFactory;
    private array $middlewareMap;

    public function __construct(MiddlewareFactoryInterface $middlewareFactory, array $middlewareMap)
    {
        $this->middlewareFactory = $middlewareFactory;
        $this->middlewareMap = $middlewareMap;
    }

    public function create(ServerRequestInterface $request): MiddlewarePipeInterface
    {
        $pipe = new MiddlewarePipe();
        foreach ($this->middlewareMap as $path => $middleware) {
            foreach ($middleware as $middlewareClass => $config) {
                $middleware = $this->middlewareFactory->create($middlewareClass, (array) $config);
                $pipe->pipe(new RouteParamsPathMiddlewareDecorator($path, $middleware));
            }
        }

        return $pipe;
    }
}

<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Factory;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareFactoryInterface
{
    public function create(string $middlewareClass, array $params = []): MiddlewareInterface;

    public function canCreate(string $middlewareClass): bool;
}

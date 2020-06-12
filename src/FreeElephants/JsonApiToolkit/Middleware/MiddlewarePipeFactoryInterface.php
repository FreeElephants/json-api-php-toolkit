<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use Laminas\Stratigility\MiddlewarePipeInterface;
use Psr\Http\Message\ServerRequestInterface;

interface MiddlewarePipeFactoryInterface
{
    public function create(ServerRequestInterface $request): MiddlewarePipeInterface;
}

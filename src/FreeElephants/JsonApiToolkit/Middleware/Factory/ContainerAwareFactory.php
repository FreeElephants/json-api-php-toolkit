<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Factory;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class ContainerAwareFactory implements MiddlewareFactoryInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(string $middlewareClass, array $params = []): MiddlewareInterface
    {
        return $this->container->get($middlewareClass);
    }

    public function canCreate(string $middlewareClass): bool
    {
        return true;
    }
}

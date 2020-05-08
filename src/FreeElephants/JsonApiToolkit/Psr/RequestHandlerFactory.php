<?php

namespace FreeElephants\JsonApiToolkit\Psr;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(string $className): RequestHandlerInterface
    {
        return $this->container->get($className);
    }
}

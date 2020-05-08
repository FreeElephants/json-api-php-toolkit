<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Factory;

use FreeElephants\JsonApiToolkit\Middleware\Auth\Authorization;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthorizationFactory implements MiddlewareFactoryInterface
{
    private ContainerInterface $container;
    private JsonApiResponseFactory $jsonApiResponseFactory;

    public function __construct(ContainerInterface $container, JsonApiResponseFactory $jsonApiResponseFactory)
    {
        $this->container = $container;
        $this->jsonApiResponseFactory = $jsonApiResponseFactory;
    }

    public function create(string $middlewareClass, array $params = []): MiddlewareInterface
    {
        $policy = $this->container->get(array_shift($params));

        return new Authorization($policy, $this->jsonApiResponseFactory);
    }

    public function canCreate(string $middlewareClass): bool
    {
        return is_a($middlewareClass, Authorization::class, true);
    }
}

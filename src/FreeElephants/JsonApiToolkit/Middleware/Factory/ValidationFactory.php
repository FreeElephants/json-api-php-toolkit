<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Factory;

use FreeElephants\JsonApiToolkit\Middleware\Validation\Validation;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use FreeElephants\Validation\ValidatorInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class ValidationFactory implements MiddlewareFactoryInterface
{
    private JsonApiResponseFactory $jsonApiResponseFactory;
    private ValidatorInterface $validator;
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container, JsonApiResponseFactory $jsonApiResponseFactory, ValidatorInterface $validator)
    {
        $this->jsonApiResponseFactory = $jsonApiResponseFactory;
        $this->validator = $validator;
        $this->container = $container;
    }

    public function create(string $middlewareClass, array $params = []): MiddlewareInterface
    {
        $rules = $this->container->get(array_shift($params));

        return new Validation($this->jsonApiResponseFactory, $this->validator, $rules);
    }

    public function canCreate(string $middlewareClass): bool
    {
        return is_a($middlewareClass, Validation::class, true);
    }
}

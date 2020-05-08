<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Factory;

use FreeElephants\JsonApiToolkit\Middleware\Validation\Validation;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use FreeElephants\Validation\Rules;
use FreeElephants\Validation\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ValidationFactoryTest extends TestCase
{
    public function testCreate()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willReturn($this->createMock(Rules::class));
        $jsonApiResponseFactory = $this->createMock(JsonApiResponseFactory::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $factory = new ValidationFactory($container, $jsonApiResponseFactory, $validator);
        $middleware = $factory->create(Validation::class, [Rules::class]);
        $this->assertInstanceOf(Validation::class, $middleware);
    }
}

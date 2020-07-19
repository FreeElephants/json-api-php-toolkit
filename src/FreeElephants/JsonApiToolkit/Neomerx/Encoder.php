<?php

namespace FreeElephants\JsonApiToolkit\Neomerx;

use Neomerx\JsonApi\Contracts\Factories\FactoryInterface;
use Neomerx\JsonApi\Encoder\Encoder as NeomerxEncoder;
use Psr\Container\ContainerInterface;

class Encoder extends NeomerxEncoder
{
    private static ContainerInterface $container;

    public static function setPsrContainer(ContainerInterface $container)
    {
        self::$container = $container;
    }

    protected static function createFactory(): FactoryInterface
    {
        return new Factory(self::$container);
    }
}

<?php

namespace FreeElephants\JsonApiToolkit\Neomerx;

use Neomerx\JsonApi\Contracts\Schema\SchemaContainerInterface;
use Neomerx\JsonApi\Factories\Factory as NeomerxFactory;
use Psr\Container\ContainerInterface;

class Factory extends NeomerxFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createSchemaContainer(iterable $schemas): SchemaContainerInterface
    {
        return new PsrContainerAwareSchemaContainer($this->container, $this, $schemas);
    }
}

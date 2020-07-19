<?php

namespace FreeElephants\JsonApiToolkit\Neomerx;

use Doctrine\Persistence\Proxy;
use Neomerx\JsonApi\Contracts\Factories\FactoryInterface;
use Neomerx\JsonApi\Contracts\Schema\SchemaInterface;
use Neomerx\JsonApi\Schema\SchemaContainer;
use Psr\Container\ContainerInterface;

/**
 * Instantiate Schemas via Psr\Container.
 */
class PsrContainerAwareSchemaContainer extends SchemaContainer
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container, FactoryInterface $factory, iterable $schemas)
    {
        $this->container = $container;
        parent::__construct($factory, $schemas);
    }

    /**
     * Clear Doctrine Entity Proxies class names.
     */
    protected function getResourceType($resource): string
    {
        $class = get_class($resource);
        $pos = strrpos($class, '\\' . Proxy::MARKER . '\\');

        if ($pos === false) {
            return $class;
        }

        return substr($class, $pos + Proxy::MARKER_LENGTH + 2);
    }

    protected function createSchemaFromClassName(string $className): SchemaInterface
    {
        return $this->container->get($className);
    }
}

<?php

namespace FreeElephants\JsonApiToolkit\Neomerx\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Proxy;
use Neomerx\JsonApi\Contracts\Factories\FactoryInterface;
use Neomerx\JsonApi\Schema\SchemaContainer;

class DoctrineProxyAwareSchemaContainer extends SchemaContainer
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, FactoryInterface $factory, iterable $schemas)
    {
        parent::__construct($factory, $schemas);
        $this->entityManager = $entityManager;
    }

    protected function getResourceType($resource): string
    {
        $class = get_class($resource);
        $pos = strrpos($class, '\\' . Proxy::MARKER . '\\');

        if (false === $pos) {
            return $class;
        }

        return substr($class, $pos + Proxy::MARKER_LENGTH + 2);
    }
}

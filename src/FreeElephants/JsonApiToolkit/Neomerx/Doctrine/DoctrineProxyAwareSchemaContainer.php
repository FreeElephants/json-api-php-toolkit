<?php

namespace FreeElephants\JsonApiToolkit\Neomerx\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
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
        $className = get_class($resource);
        if ($this->entityManager->getMetadataFactory()->hasMetadataFor($className)) {
            $className = $this->entityManager->getClassMetadata($className)->getName();
        }

        return $className;
    }
}

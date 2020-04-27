<?php

namespace FreeElephants\JsonApiToolkit\Neomerx;

use Doctrine\ORM\EntityManagerInterface;
use FreeElephants\JsonApiToolkit\Neomerx\Doctrine\DoctrineProxyAwareSchemaContainer;
use Neomerx\JsonApi\Contracts\Schema\SchemaContainerInterface;
use Neomerx\JsonApi\Factories\Factory as NeomerxFactory;

class Factory extends NeomerxFactory
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createSchemaContainer(iterable $schemas): SchemaContainerInterface
    {
        return new DoctrineProxyAwareSchemaContainer($this->entityManager, $this, $schemas);
    }
}

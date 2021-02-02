<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use InvalidArgumentException;

class ResourcesCollectionRoute implements RouteInterface
{
    protected MappingConfigProvider $mappingProvider;

    protected string $version;
    protected string $resource;

    public function __construct(MappingConfigProvider $mappingProvider, string $apiVersion, string $resourceType)
    {
        if (!$mappingProvider->isEndpointToDocumentMapped($resourceType)) {
            throw new InvalidArgumentException('Resource type in provided path not recognized');
        }

        $this->mappingProvider = $mappingProvider;

        $this->version = $apiVersion;
        $this->resource = $resourceType;
    }

    public function getType(): int
    {
        return RouteInterface::TYPE_RESOURCES_COLLECTION;
    }

    public function getEndpointTypeName(): string
    {
        return $this->resource;
    }

    public function getRouteParamName(): ?string
    {
        return null;
    }

    public function getRelationshipOriginName(): string
    {
        return $this->resource;
    }

    public function getApiVersion(): string
    {
        return $this->version;
    }

    public function getResourceName(): string
    {
        return $this->resource;
    }
}

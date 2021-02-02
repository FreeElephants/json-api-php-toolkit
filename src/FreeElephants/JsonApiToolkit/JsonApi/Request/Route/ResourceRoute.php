<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;

class ResourceRoute extends ResourcesCollectionRoute
{
    protected string $id;

    public function __construct(MappingConfigProvider $mappingProvider, string $apiVersion, string $resourceType, string $id)
    {
        parent::__construct($mappingProvider, $apiVersion, $resourceType);

        $this->id = $id;
    }

    public function getType(): int
    {
        return RouteInterface::TYPE_RESOURCE;
    }

    public function getRouteParamName(): ?string
    {
        return $this->mappingProvider->getRouteParamKeyByResourceType($this->getEndpointTypeName());
    }

    public function getId(): string
    {
        return $this->id;
    }
}

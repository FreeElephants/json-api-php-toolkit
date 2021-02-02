<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use InvalidArgumentException;

class RelationshipRoute extends ResourceRoute
{
    protected string $relationship;

    public function __construct(MappingConfigProvider $mappingProvider, string $apiVersion, string $resourceType, string $id, string $relationshipType)
    {
        if (!$mappingProvider->isEndpointToDocumentMapped($relationshipType)) {
            throw new InvalidArgumentException('Relationship in provided path not recognized');
        }

        parent::__construct($mappingProvider, $apiVersion, $resourceType, $id);

        $this->relationship = $relationshipType;
    }

    public function getType(): int
    {
        return RouteInterface::TYPE_RELATIONSHIP;
    }

    public function getEndpointTypeName(): string
    {
        return $this->mappingProvider->getDocumentTypeByEndpointType($this->relationship);
    }

    public function getRouteParamName(): ?string
    {
        return $this->mappingProvider->getRouteParamKeyByResourceType($this->resource);
    }

    public function getRelationshipName(): string
    {
        return $this->relationship;
    }
}

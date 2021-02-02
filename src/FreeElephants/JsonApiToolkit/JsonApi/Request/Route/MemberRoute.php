<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use InvalidArgumentException;

class MemberRoute extends ResourceRoute
{
    protected string $member;

    public function __construct(MappingConfigProvider $mappingProvider, string $apiVersion, string $resourceType, string $id, string $memberType)
    {
        if (!$mappingProvider->isEndpointToDocumentMapped($memberType)) {
            throw new InvalidArgumentException('Member type in provided path not recognized');
        }

        parent::__construct($mappingProvider, $apiVersion, $resourceType, $id);

        $this->member = $memberType;
    }

    public function getType(): int
    {
        return RouteInterface::TYPE_MEMBER;
    }

    public function getEndpointTypeName(): string
    {
        return $this->mappingProvider->getDocumentTypeByEndpointType($this->member);
    }

    public function getRouteParamName(): ?string
    {
        return $this->mappingProvider->getRouteParamKeyByResourceType($this->resource);
    }

    public function getRelationshipOriginName(): string
    {
        return $this->member;
    }

    public function getMemberName(): string
    {
        return $this->member;
    }
}

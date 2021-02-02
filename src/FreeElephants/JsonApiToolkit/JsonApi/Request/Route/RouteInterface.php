<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

interface RouteInterface
{
    public const TYPE_RESOURCES_COLLECTION = 0;
    public const TYPE_RESOURCE = 1;
    public const TYPE_RELATIONSHIP = 2;
    public const TYPE_MEMBER = 3;

    public function getType(): int;

    public function getEndpointTypeName(): string;

    public function getRouteParamName(): ?string;

    public function getResourceName(): string;

    public function getRelationshipOriginName(): string;
}

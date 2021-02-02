<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request;

class MappingConfigProvider
{
    public const ENDPOINT_TYPE_TO_DOCUMENT_TYPE = 0;
    public const RESOURCE_TYPE_TO_ROUTE_PARAM_KEY = 1;
    public const TYPE_TO_CLASSNAME = 2;

    protected array $endpointTypeToDocumentTypeMapping;
    protected array $resourceTypeToRouteParamKeyTypeMapping;
    protected array $typenameToClassnameMapping;

    /**
     * @example
     * [
     *     ENDPOINT_TYPE_TO_DOCUMENT_TYPE => [
     *         'foo' => 'bar',
     *     ],
     * ]
     *
     * @param array<int, array<string, string>> $mapping
     */
    public function __construct(array $mapping)
    {
        $this->endpointTypeToDocumentTypeMapping = $mapping[self::ENDPOINT_TYPE_TO_DOCUMENT_TYPE] ?? [];
        $this->resourceTypeToRouteParamKeyTypeMapping = $mapping[self::RESOURCE_TYPE_TO_ROUTE_PARAM_KEY] ?? [];
        $this->typenameToClassnameMapping = $mapping[self::TYPE_TO_CLASSNAME] ?? [];
    }

    public function isEndpointToDocumentMapped(string $endpointType): bool
    {
        return array_key_exists($endpointType, $this->endpointTypeToDocumentTypeMapping);
    }

    public function isResourceToRouteParamKeyMapped(string $resourceType): bool
    {
        return array_key_exists($resourceType, $this->resourceTypeToRouteParamKeyTypeMapping);
    }

    public function isTypenameToClassnameMapped(string $typename): bool
    {
        return array_key_exists($typename, $this->typenameToClassnameMapping);
    }

    public function getDocumentTypeByEndpointType(string $endpointType): ?string
    {
        return $this->endpointTypeToDocumentTypeMapping[$endpointType] ?? null;
    }

    public function getRouteParamKeyByResourceType(string $resourceType): ?string
    {
        return $this->resourceTypeToRouteParamKeyTypeMapping[$resourceType] ?? null;
    }

    public function getClassnameByTypename(?string $typename): ?string
    {
        return $this->typenameToClassnameMapping[$typename] ?? null;
    }
}

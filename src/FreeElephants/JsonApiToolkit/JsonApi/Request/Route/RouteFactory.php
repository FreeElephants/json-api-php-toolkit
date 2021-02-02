<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use InvalidArgumentException;

class RouteFactory
{
    private MappingConfigProvider $mappingProvider;
    private RouteRulesProvider $routeRulesProvider;

    public function __construct(MappingConfigProvider $mappingProvider, RouteRulesProvider $routeRulesProvider)
    {
        $this->mappingProvider = $mappingProvider;
        $this->routeRulesProvider = $routeRulesProvider;
    }

    public function create(string $path): RouteInterface
    {
        $routeType = $this->guessType($path);
        $parts = explode('/', ltrim($path, '/'));
        if (!$this->routeRulesProvider->hasApiVersion()) {
            array_unshift($parts, '');
        }

        switch ($routeType) {
            case RouteInterface::TYPE_RESOURCES_COLLECTION:
                return new ResourcesCollectionRoute($this->mappingProvider, ...$parts);
            case RouteInterface::TYPE_RESOURCE:
                return new ResourceRoute($this->mappingProvider, ...$parts);
            case RouteInterface::TYPE_RELATIONSHIP:
                return new RelationshipRoute($this->mappingProvider, ...array_filter($parts, fn ($value) => $value !== 'relationships'));
            case RouteInterface::TYPE_MEMBER:
                return new MemberRoute($this->mappingProvider, ...$parts);
            default:
                throw new InvalidArgumentException('Type of provided path not recognized');
        }
    }

    private function guessType(string $path): int
    {
        $routeTypes = [
            RouteInterface::TYPE_RESOURCES_COLLECTION,
            RouteInterface::TYPE_RESOURCE,
            RouteInterface::TYPE_RELATIONSHIP,
            RouteInterface::TYPE_MEMBER,
        ];
        foreach ($routeTypes as $type) {
            if (preg_match($this->routeRulesProvider->getRegexForRoute($type), $path)) {
                return $type;
            }
        }

        return -1;
    }
}

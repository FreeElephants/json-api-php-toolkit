<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

class RouteRulesProvider
{
    /**
     * Route type to route regex.
     *
     * @var array<int, string>
     */
    private array $routeRules;

    private bool $hasApiVersion;

    public function __construct(array $routeRules, bool $hasApiVersion = false)
    {
        $this->routeRules = $routeRules;
        $this->hasApiVersion = $hasApiVersion;
    }

    public function getRegexForRoute(int $routeType): ?string
    {
        return $this->routeRules[$routeType] ?? null;
    }

    public function hasApiVersion(): bool
    {
        return $this->hasApiVersion;
    }
}

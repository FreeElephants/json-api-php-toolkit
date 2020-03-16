<?php

namespace FreeElephants\JsonApiToolkit\Routing;

interface RoutesMapBuilderInterface
{

    public function buildRoutesMap(string $openApiSpecification): array;
}
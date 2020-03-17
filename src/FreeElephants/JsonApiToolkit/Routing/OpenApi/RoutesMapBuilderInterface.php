<?php

namespace FreeElephants\JsonApiToolkit\Routing\OpenApi;

interface RoutesMapBuilderInterface
{

    public function buildRoutesMap(string $openApiSpecification): array;
}
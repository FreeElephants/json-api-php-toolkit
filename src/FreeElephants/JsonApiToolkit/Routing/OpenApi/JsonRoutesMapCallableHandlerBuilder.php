<?php

namespace FreeElephants\JsonApiToolkit\Routing\OpenApi;

class JsonRoutesMapCallableHandlerBuilder implements RoutesMapBuilderInterface
{

    public function buildRoutesMap(string $openApiSpecification): array
    {
        $decodedJson = json_decode($openApiSpecification, true);
        $paths = $decodedJson['paths'] ?? [];
        $routesMap = [];
        foreach ($paths as $path => $pathParams) {
            foreach ($pathParams as $method => $methodParams) {
                if (isset($methodParams['operationId'])) {
                    $routesMap[$path][strtoupper($method)] = $methodParams['operationId'];
                }
            }
        }

        return $routesMap;
    }
}
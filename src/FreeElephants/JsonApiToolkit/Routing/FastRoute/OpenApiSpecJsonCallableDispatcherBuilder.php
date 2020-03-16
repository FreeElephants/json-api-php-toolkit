<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;

class OpenApiSpecJsonCallableDispatcherBuilder implements OpenApiSpecDispatcherBuilderInterface
{

    public function buildDispatcher(string $openApiSpecification): Dispatcher
    {
        $swaggerData = json_decode($openApiSpecification);
        $routesMap = [];
        foreach ($swaggerData['paths'] as $path => $methods) {
            $routesMap[$path] = [];
            foreach ($methods as $method => $methodParams) {
                $operationMembers                      = explode('::', $methodParams['operationId']);
                $handlerClassName                      = $operationMembers[0];
                $routesMap[$path][strtoupper($method)] = $handlerClassName;
            }
        }

        return $routesMap;
    }
}
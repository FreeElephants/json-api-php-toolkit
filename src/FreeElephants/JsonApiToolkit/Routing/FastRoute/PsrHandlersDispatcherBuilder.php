<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Server\RequestHandlerInterface;
use function FastRoute\simpleDispatcher;

class PsrHandlersDispatcherBuilder implements DispatcherBuilderInterface
{

    public function buildDispatcher(array $routesMap): Dispatcher
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) use ($routesMap) {
            foreach ($routesMap as $path => $route) {
                foreach ($route as $method => $handler) {
                    if (is_callable($handler) && strpos('::', $handler) > 0) {
                        $handler = array_pop(explode('::', $handler));
                    }
                    if ($this->verifyHandler($handler)) {
                        $routeCollector->addRoute($method, $path, $handler);
                    }
                }
            }
        });

        return $dispatcher;
    }

    private function verifyHandler($handler): bool
    {
        if (is_subclass_of($handler, RequestHandlerInterface::class)) {
            return true;
        }
        return false;
    }
}
<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use cebe\openapi\Reader;
use cebe\openapi\spec\Operation;
use cebe\openapi\spec\PathItem;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class DispatcherFactory implements DispatcherFactoryInterface
{

    public function buildDispatcher(string $openApiDocumentSource): Dispatcher
    {
        $openapi = Reader::readFromYaml($openApiDocumentSource);
        $paths = $openapi->paths->getIterator();
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) use ($paths) {
            /**@var PathItem $pathItem */
            foreach ($paths as $path => $pathItem) {
                /**@var Operation $operation */
                foreach ($pathItem->getOperations() as $method => $operation) {
                    $httpMethod = $this->normalizeHttpMethod($method);
                    $handler = $this->normalizeOperationHandler($operation);
                    $routeCollector->addRoute($httpMethod, $path, $handler);
                }
//                foreach ($route as $method => $handler) {
//                    if (is_callable($handler) && strpos($handler, '::handle') > 0) {
//                        $callbackParts = explode('::', $handler);
//                        $handlerClassName = array_shift($callbackParts);
//                        $handler = $handlerClassName;
//                    }
//                    if ($this->verifyHandler($handler)) {

//                    }
//                }
            }
        });

        return $dispatcher;
    }

    private function normalizeHttpMethod(string $method): string
    {
        return strtoupper($method);
    }

    private function normalizeOperationHandler(Operation $operation): string
    {
        return $operation->operationId;
    }

//    private function verifyHandler($handler): bool
//    {
//        if (is_subclass_of($handler, RequestHandlerInterface::class)) {
//            return true;
//        }
//        return false;
//    }
}
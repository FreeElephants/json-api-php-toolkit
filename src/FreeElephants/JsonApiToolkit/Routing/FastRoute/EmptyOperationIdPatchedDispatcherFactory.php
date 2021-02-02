<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use cebe\openapi\spec\Operation;
use cebe\openapi\spec\PathItem;
use FastRoute\DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;
use FreeElephants\JsonApiToolkit\JsonApi\Request\Route\RouteFactory;
use FreeElephants\JsonApiToolkit\Routing\FastRoute\Dispatcher\JsonApiDispatcher;
use function FastRoute\simpleDispatcher;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\OpenApiDocumentParserInterface;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\YamlStringParser;

/**
 * TODO extract to library.
 */
class EmptyOperationIdPatchedDispatcherFactory implements DispatcherFactoryInterface
{
    private OpenApiDocumentParserInterface $apiDocumentParser;
    private OperationHandlerNormalizerInterface $operationHandlerNormalizer;
    private RouteFactory $routeFactory;

    public function __construct(
        RouteFactory $routeFactory,
        OperationHandlerNormalizerInterface $operationHandlerNormalizer = null,
        OpenApiDocumentParserInterface $apiDocumentParser = null
    )
    {
        $this->operationHandlerNormalizer = $operationHandlerNormalizer ?: new DefaultOperationHandlerNormalizer();
        $this->apiDocumentParser = $apiDocumentParser ?: new YamlStringParser();
        $this->routeFactory = $routeFactory;
    }

    public function buildDispatcher(string $openApiDocumentSource): Dispatcher
    {
        $openapi = $this->apiDocumentParser->parse($openApiDocumentSource);
        $paths = $openapi->paths->getIterator();

        $routeCollector = $this->buildRouteCollector();
        foreach ($paths as $path => $pathItem) {
            foreach ($pathItem->getOperations() as $method => $operation) {
                $httpMethod = $this->normalizeHttpMethod($method);
                $handler = $this->normalizeOperationHandler($operation);
                $routeCollector->addRoute($httpMethod, $path, $handler);
            }
        }

        return new JsonApiDispatcher($routeCollector->getData(), $this->routeFactory);
    }

    private function buildDataGenerator(): DataGenerator
    {
        return new DataGenerator\GroupCountBased();
    }

    private function buildRouteParser(): RouteParser
    {
        return new RouteParser\Std();
    }

    private function buildRouteCollector(): RouteCollector
    {
        return new RouteCollector($this->buildRouteParser(), $this->buildDataGenerator());
    }

    private function normalizeHttpMethod(string $method): string
    {
        return strtoupper($method);
    }

    private function normalizeOperationHandler(Operation $operation): string
    {
        return $this->operationHandlerNormalizer->normalize($operation->operationId ?? '');
    }
}

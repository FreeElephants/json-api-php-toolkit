<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use cebe\openapi\spec\Operation;
use cebe\openapi\spec\PathItem;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\OpenApiDocumentParserInterface;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\YamlStringParser;
use function FastRoute\simpleDispatcher;

class DispatcherFactory implements DispatcherFactoryInterface
{
    private OpenApiDocumentParserInterface $apiDocumentParser;
    /**
     * @var OperationHandlerNormalizerInterface
     */
    private OperationHandlerNormalizerInterface $operationHandlerNormalizer;
    private ?string $optionsHandler;

    public function __construct(
        OperationHandlerNormalizerInterface $operationHandlerNormalizer = null,
        OpenApiDocumentParserInterface $apiDocumentParser = null,
        string $optionsHandler = null
    )
    {
        $this->operationHandlerNormalizer = $operationHandlerNormalizer ?: new DefaultOperationHandlerNormalizer();
        $this->apiDocumentParser = $apiDocumentParser ?: new YamlStringParser();
        $this->optionsHandler = $optionsHandler;
    }

    public function buildDispatcher(string $openApiDocumentSource): Dispatcher
    {
        $openapi = $this->apiDocumentParser->parse($openApiDocumentSource);
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
                if (null !== $this->optionsHandler) {
                    $routeCollector->addRoute('OPTIONS', $path, $this->optionsHandler);
                }
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

}
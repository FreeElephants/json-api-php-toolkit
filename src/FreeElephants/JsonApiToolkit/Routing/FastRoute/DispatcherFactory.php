<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use cebe\openapi\spec\Operation;
use cebe\openapi\spec\PathItem;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\OpenApiDocumentParserInterface;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\YamlStringParser;
use InvalidArgumentException;
use function FastRoute\simpleDispatcher;

class DispatcherFactory implements DispatcherFactoryInterface
{
    private OpenApiDocumentParserInterface $apiDocumentParser;
    private OperationHandlerNormalizerInterface $operationHandlerNormalizer;
    private ?string $dispatcherClass;
    private ?string $optionsHandlerFactoryClass;

    public function __construct(
        OperationHandlerNormalizerInterface $operationHandlerNormalizer = null,
        OpenApiDocumentParserInterface $apiDocumentParser = null
    )
    {
        $this->operationHandlerNormalizer = $operationHandlerNormalizer ?: new DefaultOperationHandlerNormalizer();
        $this->apiDocumentParser = $apiDocumentParser ?: new YamlStringParser();
        $this->dispatcherClass = null;
        $this->optionsHandlerFactoryClass = null;
    }

    public function buildDispatcher(string $openApiDocumentSource): Dispatcher
    {
        $openapi = $this->apiDocumentParser->parse($openApiDocumentSource);
        $paths = $openapi->paths->getIterator();
        $optionsHandlerFactoryClass = $this->optionsHandlerFactoryClass;

        $dispatcherOptions = [];
        if (null !== $this->dispatcherClass) {
            $dispatcherOptions['dispatcher'] = $this->dispatcherClass;
        }

        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) use ($paths, $optionsHandlerFactoryClass) {
            /**@var PathItem $pathItem */
            foreach ($paths as $path => $pathItem) {
                /**@var Operation $operation */
                foreach ($pathItem->getOperations() as $method => $operation) {
                    $httpMethod = $this->normalizeHttpMethod($method);
                    $handler = $this->normalizeOperationHandler($operation);
                    $routeCollector->addRoute($httpMethod, $path, $handler);
                }

                if (null !== $optionsHandlerFactoryClass) {
                    $routeCollector->addRoute(
                        'OPTIONS',
                        $path,
                        sprintf('%s::handle', $optionsHandlerFactoryClass)
                    );
                }
            }
        }, $dispatcherOptions);

        return $dispatcher;
    }

    public function setOptionsHandlerFactory(RequestHandlerFactoryInterface $factory)
    {
        $this->setCustomDispatcher(OptionableGroupCountBasedDispatcher::class);
        $this->optionsHandlerFactoryClass = $factory->getHandlerClass();
    }

    protected function setCustomDispatcher(string $dispatcherClass)
    {
        if (!is_subclass_of($dispatcherClass, Dispatcher::class)) {
            throw new InvalidArgumentException(sprintf('Invalid dispatcher class: %s', $dispatcherClass));
        }

        $this->dispatcherClass = $dispatcherClass;
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
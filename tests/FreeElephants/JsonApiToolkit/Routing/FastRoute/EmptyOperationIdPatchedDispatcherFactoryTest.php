<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;
use FreeElephants\JsonApiToolkit\JsonApi\Request\JsonApiServerRequestInterface;
use FreeElephants\JsonApiToolkit\JsonApi\Request\Route\RouteFactory;
use FreeElephants\JsonApiToolkit\JsonApi\Request\Route\RouteInterface;
use PHPUnit\Framework\TestCase;

class EmptyOperationIdPatchedDispatcherFactoryTest extends TestCase
{
    public function testBuildDispatcher()
    {
        $factory = new EmptyOperationIdPatchedDispatcherFactory(
            $rf = $this->createMock(RouteFactory::class)
        );
        $rf->expects($this->once())->method('create')->willReturn(
            $r = $this->createMock(RouteInterface::class)
        );

        $dispatcher = $factory->buildDispatcher(<<<YAML
paths: 
  /articles:
    get:
      operationId: ArticlesCollectionHandler::handle
YAML
        );
        $this->assertSame(
            [Dispatcher::FOUND, 'ArticlesCollectionHandler::handle', [
                JsonApiServerRequestInterface::ATTRIBUTE_ROUTE_NAME => $r
            ]],
            $dispatcher->dispatch('GET', '/articles')
        );
    }
}

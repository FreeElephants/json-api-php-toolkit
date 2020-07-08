<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;
use FreeElephants\JsonApiToolkit\AbstractTestCase;

class DispatcherFactoryTest extends AbstractTestCase
{

    public function testBuildDispatcher()
    {
        $factory = new DispatcherFactory();
        $dispatcher = $factory->buildDispatcher(<<<YAML
paths: 
  /articles:
    get:
      operationId: ArticlesCollectionHandler::handle
YAML
);
        $this->assertSame([Dispatcher::FOUND, 'ArticlesCollectionHandler::handle', []], $dispatcher->dispatch('GET', '/articles'));
    }
}

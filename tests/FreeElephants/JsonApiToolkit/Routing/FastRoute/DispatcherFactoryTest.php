<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;
use FreeElephants\JsonApiToolkit\AbstractTestCase;

class DispatcherFactoryTest extends AbstractTestCase
{
    private string $yaml;

    public function setUp(): void
    {
        parent::setUp();

        $this->yaml = <<<YAML
paths: 
  /articles:
    get:
      operationId: ArticlesCollectionGetHandler::handle
  /posts:
    get:
      operationId: PostsCollectionGetHandler::handle
    post:
      operationId: PostsCollectionPostHandler::handle
YAML;
    }

    public function testBuildDispatcher()
    {
        $factory = new DispatcherFactory();
        $dispatcher = $factory->buildDispatcher($this->yaml);

        $expected = [Dispatcher::FOUND, 'ArticlesCollectionGetHandler::handle', []];

        $actual = $dispatcher->dispatch('GET', '/articles');

        $this->assertSame($expected, $actual);
    }

    public function testOptions()
    {
        $dispatcherFactory = new DispatcherFactory(
            null,
            null
        );
        $optionsRequestHandlerFactory = new OptionsRequestHandlerFactory(OptionsRequestHandlerMock::class);
        $dispatcherFactory->setOptionsHandlerFactory($optionsRequestHandlerFactory);
        $dispatcher = $dispatcherFactory->buildDispatcher($this->yaml);

        $expect = [
            Dispatcher::FOUND,
            sprintf('%s::handle', OptionsRequestHandlerMock::class),
            ['GET', 'OPTIONS']
        ];

        $actual = $dispatcher->dispatch('OPTIONS', '/articles');

        $this->assertSame($expect, $actual);

        $expect = [
            Dispatcher::FOUND,
            sprintf('%s::handle', OptionsRequestHandlerMock::class),
            ['GET', 'OPTIONS', 'POST']
        ];

        $actual = $dispatcher->dispatch('OPTIONS', '/posts');

        $this->assertSame($expect, $actual);
    }
}
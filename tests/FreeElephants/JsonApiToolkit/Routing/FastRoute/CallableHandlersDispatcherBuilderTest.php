<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;
use FreeElephants\JsonApiToolkit\AbstractTestCase;

class CallableHandlersDispatcherBuilderTest extends AbstractTestCase
{

    public function testBuildDispatcher()
    {
        $builder = new CallableHandlersDispatcherBuilder(CallableHandlersDispatcherBuilder::ADD_ANYWAY);
        $dispatcher = $builder->buildDispatcher([
            '/pets'         => [
                'GET'  => 'listPets',
                'POST' => 'createPets',
            ],
            '/pets/{petId}' => [
                'GET' => 'showPetById',
            ],
        ]);
        $this->assertSame([Dispatcher::FOUND, 'listPets', []], $dispatcher->dispatch('GET', '/pets'));
        $this->assertSame([Dispatcher::FOUND, 'createPets', []], $dispatcher->dispatch('POST', '/pets'));
        $this->assertSame([Dispatcher::FOUND, 'showPetById', ['petId' => '123']], $dispatcher->dispatch('GET', '/pets/123'));
        $this->assertSame([Dispatcher::NOT_FOUND], $dispatcher->dispatch('GET', '/not-found'));
    }

    public function testBuildDispatcherInvalidArgumentException()
    {
        $builder = new CallableHandlersDispatcherBuilder(CallableHandlersDispatcherBuilder::THROW_EXCEPTION_IF_NOT_CALLBACK);
        $this->expectException(\InvalidArgumentException::class);
        $builder->buildDispatcher([
            '/pets' => [
                'GET' => '_functionThatNotExists_',
            ],
        ]);
    }

    public function testBuildDispatcherAddIfCallback()
    {
        $builder = new CallableHandlersDispatcherBuilder(CallableHandlersDispatcherBuilder::ADD_IF_CALLBACK);
        $dispatcher = $builder->buildDispatcher([
            '/pets' => [
                'GET'    => '_functionThatNotExists_',
                'POST'   => [$this, 'testBuildDispatcherAddIfCallback'],
                'PUT'    => [$this, 'methodThatNotExists'],
                'PATCH'  => 'json_encode',
                'DELETE' => 'FreeElephants\\JsonApiToolkit\\Routing\\FastRoute\\CallableHandlersDispatcherBuilderTest::testBuildDispatcherAddIfCallback',
            ],
        ]);

        $this->assertSame([Dispatcher::METHOD_NOT_ALLOWED, ['POST', 'PATCH', 'DELETE']], $dispatcher->dispatch('GET', '/pets'));
        $this->assertSame([Dispatcher::FOUND, [$this, 'testBuildDispatcherAddIfCallback'], []], $dispatcher->dispatch('POST', '/pets'));
        $this->assertSame([Dispatcher::METHOD_NOT_ALLOWED, ['POST', 'PATCH', 'DELETE']], $dispatcher->dispatch('PUT', '/pets'));
        $this->assertSame([Dispatcher::FOUND, 'json_encode', []], $dispatcher->dispatch('PATCH', '/pets'));
        $this->assertSame([Dispatcher::FOUND, 'FreeElephants\\JsonApiToolkit\\Routing\\FastRoute\\CallableHandlersDispatcherBuilderTest::testBuildDispatcherAddIfCallback', []], $dispatcher->dispatch('DELETE', '/pets'));
    }
}


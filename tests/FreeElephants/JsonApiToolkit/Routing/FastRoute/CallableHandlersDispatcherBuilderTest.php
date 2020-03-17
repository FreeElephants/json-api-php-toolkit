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
            ]
        ]);
        $this->assertSame([Dispatcher::FOUND, 'listPets', []], $dispatcher->dispatch('GET', '/pets'));
        $this->assertSame([Dispatcher::FOUND, 'createPets', []], $dispatcher->dispatch('POST', '/pets'));
        $this->assertSame([Dispatcher::FOUND, 'showPetById', ['petId' => '123']], $dispatcher->dispatch('GET', '/pets/123'));
        $this->assertSame([Dispatcher::NOT_FOUND], $dispatcher->dispatch('GET', '/not-found'));
    }
}
<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;
use FreeElephants\JsonApiToolkit\AbstractTestCase;
use TestHandlerStub\DeletePetsHandler;
use TestHandlerStub\GetPetsHandler;
use TestHandlerStub\PostPetsHanlder;

class PsrHandlersDispatcherBuilderTest extends AbstractTestCase
{

    public function testBuildDispatcher()
    {
        $builder = new PsrHandlersDispatcherBuilder();
        $routesMap = [
            '/pets' => [
                'GET'    => GetPetsHandler::class,
                'POST'   => $postPetsHandler = new PostPetsHanlder(),
                'DELETE' => 'TestHandlerStub\\DeletePetsHandler::handle',
                'PATCH' => 'NotPSRHandler'
            ],
        ];
        $dispatcher = $builder->buildDispatcher($routesMap);
        $this->assertSame([Dispatcher::FOUND, GetPetsHandler::class, []], $dispatcher->dispatch('GET', '/pets'));
        $this->assertSame([Dispatcher::FOUND, $postPetsHandler, []], $dispatcher->dispatch('POST', '/pets'));
        $this->assertSame([Dispatcher::FOUND, DeletePetsHandler::class, []], $dispatcher->dispatch('DELETE', '/pets'));
        $this->assertSame([Dispatcher::METHOD_NOT_ALLOWED, ['GET', 'POST', 'DELETE']], $dispatcher->dispatch('PATCH', '/pets'));
    }
}

namespace TestHandlerStub;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetPetsHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
    }
}

class PostPetsHanlder implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
    }
}

class DeletePetsHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
    }
}
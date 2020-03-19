<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FreeElephants\JsonApiToolkit\AbstractTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OperationHandlerPsrNormalizerTest extends AbstractTestCase
{

    public function testNormalizeRequestHandlerCallable()
    {
        $normalizer = new PsrHandlerClassNameNormalizer();
        $handler = $normalizer->normalize('FreeElephants\\JsonApiToolkit\\Routing\\FastRoute\\ArticlesCollectionHandler::handle');
        $this->assertSame(ArticlesCollectionHandler::class, $handler);
    }

    public function testNormalizeRequestHandlerClassName()
    {
        $normalizer = new PsrHandlerClassNameNormalizer();
        $handler = $normalizer->normalize('FreeElephants\\JsonApiToolkit\\Routing\\FastRoute\\ArticlesCollectionHandler');
        $this->assertSame(ArticlesCollectionHandler::class, $handler);
    }

    public function testNotCallbackException()
    {
        $normalizer = new PsrHandlerClassNameNormalizer();
        $this->expectException(\InvalidArgumentException::class);
        $normalizer->normalize('baz');
    }
}

class Bar
{
    public function foo()
    {
        
    }
}

class ArticlesCollectionHandler implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
    }
}
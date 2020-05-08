<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ChainDelegatingMiddlewareFactoryTest extends TestCase
{
    public function testCanNotCreateFromEmptyChain()
    {
        $factory = new ChainDelegatingMiddlewareFactory([]);
        $this->assertFalse($factory->canCreate(SomeMiddleware::class));
    }

    public function testCreate()
    {
        $factory = new ChainDelegatingMiddlewareFactory([
            new class() implements MiddlewareFactoryInterface {
                public function create(string $middlewareClass, array $params = []): MiddlewareInterface
                {
                    return new $middlewareClass($params);
                }

                public function canCreate(string $middlewareClass): bool
                {
                    return true;
                }
            },
        ]);
        $this->assertInstanceOf(SomeMiddleware::class, $factory->create(SomeMiddleware::class));
        $this->assertTrue($factory->canCreate(SomeMiddleware::class));
    }
}

class SomeMiddleware implements MiddlewareInterface
{
    /**
     * @phpstan-ignore-next-line.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
    }
}

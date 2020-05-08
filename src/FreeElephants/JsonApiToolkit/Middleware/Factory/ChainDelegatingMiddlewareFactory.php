<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Factory;

use Psr\Http\Server\MiddlewareInterface;

class ChainDelegatingMiddlewareFactory implements MiddlewareFactoryInterface
{
    /**
     * @var MiddlewareFactoryInterface[]
     */
    private array $middlewareFactoryClassMap;

    public function __construct(array $middlewareFactoryChain)
    {
        $this->middlewareFactoryClassMap = $middlewareFactoryChain;
    }

    public function create(string $middlewareClass, array $params = []): MiddlewareInterface
    {
        foreach ($this->middlewareFactoryClassMap as $concreteFactory) {
            if ($concreteFactory->canCreate($middlewareClass)) {
                return $concreteFactory->create($middlewareClass, $params);
            }
        }

        throw new \RuntimeException();
    }

    public function canCreate(string $middlewareClass): bool
    {
        foreach ($this->middlewareFactoryClassMap as $concreteFactory) {
            if ($concreteFactory->canCreate($middlewareClass)) {
                return true;
            }
        }

        return false;
    }
}

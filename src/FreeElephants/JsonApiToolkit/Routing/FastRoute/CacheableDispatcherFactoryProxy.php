<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;
use Psr\Cache\CacheItemPoolInterface;

class CacheableDispatcherFactoryProxy implements DispatcherFactoryInterface
{
    private DispatcherFactoryInterface $dispatcherFactory;
    private CacheItemPoolInterface $cacheItemPool;

    public function __construct(DispatcherFactoryInterface $dispatcherFactory, CacheItemPoolInterface $cacheItemPool)
    {
        $this->dispatcherFactory = $dispatcherFactory;
        $this->cacheItemPool = $cacheItemPool;
    }

    public function buildDispatcher(string $openApiDocumentSource): Dispatcher
    {
        $key = md5_file($openApiDocumentSource);
        $cacheItem = $this->cacheItemPool->getItem($key);
        if ($cacheItem->isHit()) {
            $dispatcher = $cacheItem->get();
        } else {
            $dispatcher = $this->dispatcherFactory->buildDispatcher($openApiDocumentSource);
            $cacheItem->set($dispatcher);
            $this->cacheItemPool->save($cacheItem);
        }

        return $dispatcher;
    }
}

<?php


namespace FreeElephants\JsonApiToolkit\Routing\FastRoute\Dispatcher;


use FastRoute\Dispatcher\GroupCountBased;
use FreeElephants\JsonApiToolkit\JsonApi\Request\JsonApiServerRequestInterface;
use FreeElephants\JsonApiToolkit\JsonApi\Request\Route\RouteFactory;

class JsonApiDispatcher extends GroupCountBased
{
    private ?RouteFactory $routeFactory = null;

    public function __construct($data, RouteFactory $routeFactory)
    {
        parent::__construct($data);
        $this->routeFactory = $routeFactory;
    }

    public function dispatch($routeData, $uri)
    {
        $result = parent::dispatch($routeData, $uri);
        if (isset($result[2])) {

            if ($this->routeFactory) {
                $result[2][JsonApiServerRequestInterface::ATTRIBUTE_ROUTE_NAME] = $this->routeFactory->create($uri);
            }
        }

        return $result;
    }
}

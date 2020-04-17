<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use Psr\Http\Server\RequestHandlerInterface;

interface RequestHandlerFactoryInterface
{
    public function getHandlerClass();
}
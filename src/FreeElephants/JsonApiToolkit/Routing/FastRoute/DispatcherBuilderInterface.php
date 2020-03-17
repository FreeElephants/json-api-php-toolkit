<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;

interface DispatcherBuilderInterface
{

    public function buildDispatcher(array $routesMap): Dispatcher;
}
<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;

interface OpenApiSpecDispatcherBuilderInterface
{

    public function buildDispatcher(string $openApiSpecification): Dispatcher;
}
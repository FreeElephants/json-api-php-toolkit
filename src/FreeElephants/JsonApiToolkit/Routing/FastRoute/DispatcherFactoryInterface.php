<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FastRoute\Dispatcher;

interface DispatcherFactoryInterface
{

    public function buildDispatcher(string $openApiDocumentSource): Dispatcher;
}

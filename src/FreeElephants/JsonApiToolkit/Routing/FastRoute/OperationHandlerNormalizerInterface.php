<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

interface OperationHandlerNormalizerInterface
{

    public function normalize(string $operationId);
}
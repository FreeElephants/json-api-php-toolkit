<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

/**
 * Return operationId as is.
 */
class DefaultOperationHandlerNormalizer implements OperationHandlerNormalizerInterface
{

    public function normalize(string $operationId)
    {
        return $operationId;
    }
}
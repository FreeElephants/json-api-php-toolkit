<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * Parse operationId string as callback, extract class name and return class name, if it implement Psr RequestHandlerInterface
 */
class PsrHandlerClassNameNormalizer implements OperationHandlerNormalizerInterface
{

    /**
     * @param string $operationId
     * @return string - class name that implement RequestHandlerInterface
     */
    public function normalize(string $operationId)
    {
        if (strpos($operationId, '::handle') > 0) {
            $callbackParts = explode('::', $operationId);
            return array_shift($callbackParts);
        } elseif (is_subclass_of($operationId, RequestHandlerInterface::class)) {
            return $operationId;
        }
        throw new \InvalidArgumentException(sprintf('%s not a Psr RequestHandlerInterface implementation', $operationId));
    }
}

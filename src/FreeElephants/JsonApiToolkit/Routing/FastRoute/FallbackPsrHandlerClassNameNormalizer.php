<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use Psr\Http\Server\RequestHandlerInterface;

class FallbackPsrHandlerClassNameNormalizer implements OperationHandlerNormalizerInterface
{
    /**
     * @return string - class name that implement RequestHandlerInterface
     */
    public function normalize(string $operationId)
    {
        if (is_callable($operationId) && strpos($operationId, '::handle') > 0) {
            $callbackParts = explode('::', $operationId);

            return array_shift($callbackParts);
        } elseif (is_subclass_of($operationId, RequestHandlerInterface::class)) {
            return $operationId;
        }

        return NotImplementedOperationHandler::class;
    }
}

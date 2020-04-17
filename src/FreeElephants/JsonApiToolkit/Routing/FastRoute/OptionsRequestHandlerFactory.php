<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use InvalidArgumentException;
use Psr\Http\Server\RequestHandlerInterface;

class OptionsRequestHandlerFactory implements RequestHandlerFactoryInterface
{
    protected string $requestHandler;

    public function __construct(string $requestHandler)
    {
        if (!is_subclass_of($requestHandler, RequestHandlerInterface::class)) {
            throw new InvalidArgumentException(sprintf('Invalid request handler class: %s', $requestHandler));
        }

        $this->requestHandler = $requestHandler;
    }

    public function getHandlerClass()
    {
        return $this->requestHandler;
    }
}
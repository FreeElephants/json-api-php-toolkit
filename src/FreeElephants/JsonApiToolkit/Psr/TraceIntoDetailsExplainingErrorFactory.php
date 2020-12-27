<?php

namespace FreeElephants\JsonApiToolkit\Psr;

use Neomerx\JsonApi\Schema\Error;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class TraceIntoDetailsExplainingErrorFactory extends ErrorFactory
{
    public function fromThrowable(Throwable $throwable, int $status, ServerRequestInterface $request, ?array $source = null): Error
    {
        $error = parent::fromThrowable($throwable, $status, $request, $source);
        $error->setDetail($throwable->getTraceAsString());

        return $error;
    }
}

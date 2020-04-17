<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OptionsRequestHandlerMock implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        throw new Exception('Not implemented');
    }
}
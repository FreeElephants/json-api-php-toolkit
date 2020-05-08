<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use DomainException;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandler implements MiddlewareInterface
{
    private JsonApiResponseFactory $responseFactory;

    public function __construct(JsonApiResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (DomainException $throwable) {
            $httpStatus = 400;
        } catch (\Throwable $throwable) {
            $httpStatus = 500;
        }

        return $this->responseFactory->createErrorResponseFromException($throwable, $httpStatus, $request);
    }
}

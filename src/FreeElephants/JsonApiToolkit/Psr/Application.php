<?php

namespace FreeElephants\JsonApiToolkit\Psr;

use FastRoute\Dispatcher;
use FreeElephants\JsonApiToolkit\Middleware\MiddlewarePipeFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application
{
    private Dispatcher $dispatcher;
    private ResponseFactoryInterface $responseFactory;
    private RequestHandlerFactory $requestHandlerFactory;
    private MiddlewarePipeFactory $middlewarePipeFactory;

    public function __construct(Dispatcher $dispatcher, ResponseFactoryInterface $responseFactory, RequestHandlerFactory $requestHandlerFactory, MiddlewarePipeFactory $middlewarePipeFactory)
    {
        $this->dispatcher = $dispatcher;
        $this->responseFactory = $responseFactory;
        $this->requestHandlerFactory = $requestHandlerFactory;
        $this->middlewarePipeFactory = $middlewarePipeFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $result = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        switch ($result[0]) {
            case Dispatcher::FOUND:
                $handlerName = $result[1];
                $attributes = $result[2];
                foreach ($attributes as $key => $value) {
                    $request = $request->withAttribute($key, $value);
                }
                $handler = $this->requestHandlerFactory->create($handlerName);
                $pipe = $this->middlewarePipeFactory->create($request);

                return $pipe->process($request, $handler);
            case Dispatcher::NOT_FOUND:
                return $this->buildNotFoundResponse();
            case Dispatcher::METHOD_NOT_ALLOWED:
                if ('OPTIONS' === $request->getMethod()) {
                    return $this->buildOptionsResponse($result[1]);
                }

                return $this->buildMethodNotAllowedResponse($result[1]);
            default:
                throw new InvalidArgumentException('Dispatcher result not recognized');
        }
    }

    private function buildNotFoundResponse(): ResponseInterface
    {
        return $this->responseFactory->createResponse(404);
    }

    private function buildMethodNotAllowedResponse(array $allowedMethods): ResponseInterface
    {
        $allowedMethods[] = 'OPTIONS';

        return $this->responseFactory->createResponse(405)
            ->withHeader('Allow', join(', ', $allowedMethods));
    }

    private function buildOptionsResponse(array $allowedMethods): ResponseInterface
    {
        $allowedMethods[] = 'OPTIONS';

        return $this->responseFactory->createResponse()
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', join(', ', $allowedMethods));
    }
}

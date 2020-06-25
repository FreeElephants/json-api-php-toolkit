<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BodyParser implements MiddlewareInterface
{
    private JsonApiResponseFactory $responseFactory;

    public function __construct(JsonApiResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request->getBody()->rewind();

        $decodedBody = json_decode($request->getBody()->getContents(), true);

        if ($decodedBody === null && json_last_error() !== JSON_ERROR_NONE) {
            return $this->responseFactory->createSingleErrorResponse(
                'Provided json is invalid',
                StatusCodeInterface::STATUS_BAD_REQUEST,
                $request
            );
        }

        $request = $request->withParsedBody($decodedBody);

        return $handler->handle($request);
    }
}

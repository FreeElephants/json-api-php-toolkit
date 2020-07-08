<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidSecurity;
use League\OpenAPIValidation\PSR7\ServerRequestValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class SwaggerSpecificationRequestValidator implements MiddlewareInterface
{
    private ServerRequestValidator $validator;
    private JsonApiResponseFactory $responseFactory;

    public function __construct(ServerRequestValidator $validator, JsonApiResponseFactory $responseFactory)
    {
        $this->validator = $validator;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request->getBody()->rewind();

        try {
            $this->validator->validate($request);
        } catch (InvalidSecurity $e) {
            return $this->responseFactory->createErrorResponseFromException($e, StatusCodeInterface::STATUS_UNAUTHORIZED, $request);
        } catch (Throwable $e) {
            return $this->responseFactory->createErrorResponseFromException($e, StatusCodeInterface::STATUS_BAD_REQUEST, $request);
        }

        return $handler->handle($request);
    }
}

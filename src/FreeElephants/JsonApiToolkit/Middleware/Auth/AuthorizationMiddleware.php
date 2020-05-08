<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Auth;

use FreeElephants\JsonApiToolkit\Middleware\Auth\Exception\UnknownPolicyCheckResultException;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthorizationMiddleware implements MiddlewareInterface
{
    private PolicyInterface $policy;

    private JsonApiResponseFactory $responseFactory;

    public function __construct(PolicyInterface $policy, JsonApiResponseFactory $responseFactory)
    {
        $this->policy = $policy;
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $result = $this->policy->check($request);
        switch ($result) {
            case PolicyInterface::RESULT_ALLOW:
                return $handler->handle($request);
                break;
            case PolicyInterface::RESULT_UNAUTHORIZED:
                return $this->createUnauthorizedResponse($request);
                break;
            case PolicyInterface::RESULT_FORBIDDEN:
                return $this->createForbiddenResponse($request);
                break;

            default:
                throw new UnknownPolicyCheckResultException();
        }
    }

    private function createUnauthorizedResponse(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createSingleErrorResponse('Action require authentication', 401, $request);
    }

    private function createForbiddenResponse(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createSingleErrorResponse('Action require authorization', 403, $request);
    }
}

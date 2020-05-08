<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Auth;

use FreeElephants\JsonApiToolkit\AbstractHttpTestCase;
use FreeElephants\JsonApiToolkit\Psr\ErrorFactory;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationMiddlewareTest extends AbstractHttpTestCase
{
    public function testProcessPositive()
    {
        $policy = $this->createPolicyMock();
        $middleware = new Authorization($policy, $this->createJsonApiResponseFactory());
        $request = $this->createServerRequest('GET', '/v1/users/42');
        $handlerSpy = $this->createRequestHandlerWithAssertions(function (ServerRequestInterface $passedRequest) use ($request) {
            $this->assertSame($request, $passedRequest);

            return $this->createResponse();
        });

        $response = $middleware->process($request, $handlerSpy);
        $this->assertResponseIsSuccess($response);
    }

    public function testUnauthorized()
    {
        $policy = $this->createPolicyMock(PolicyInterface::RESULT_UNAUTHORIZED);
        $middleware = new Authorization($policy, $this->createJsonApiResponseFactory());
        $request = $this->createServerRequest('GET', '/v1/users/42');
        $handlerSpy = $this->createRequestHandlerWithAssertions(function (ServerRequestInterface $passedRequest) use ($request) {
            $this->assertSame($request, $passedRequest);

            return $this->createResponse();
        });

        $response = $middleware->process($request, $handlerSpy);
        $this->assertResponseHasStatus($response, 401);
    }

    public function testForbidden()
    {
        $policy = $this->createPolicyMock(PolicyInterface::RESULT_FORBIDDEN);
        $middleware = new Authorization($policy, $this->createJsonApiResponseFactory());
        $request = $this->createServerRequest('GET', '/v1/users/42');
        $handlerSpy = $this->createRequestHandlerWithAssertions(function (ServerRequestInterface $passedRequest) use ($request) {
            $this->assertSame($request, $passedRequest);

            return $this->createResponse();
        });

        $response = $middleware->process($request, $handlerSpy);
        $this->assertResponseHasStatus($response, 403);
    }

    private function createPolicyMock(int $result = PolicyInterface::RESULT_ALLOW)
    {
        $policy = $this->createMock(PolicyInterface::class);
        $policy->method('check')->willReturn($result);

        return $policy;
    }

    private function createJsonApiResponseFactory(): JsonApiResponseFactory
    {
        return new JsonApiResponseFactory($this->createMock(EncoderInterface::class), $this, $this->createMock(ErrorFactory::class));
    }
}

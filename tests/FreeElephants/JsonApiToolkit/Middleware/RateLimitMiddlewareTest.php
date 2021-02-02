<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use FreeElephants\JsonApiToolkit\AbstractHttpTestCase;
use FreeElephants\JsonApiToolkit\RateLimiter\RateConfig;
use FreeElephants\JsonApiToolkit\RateLimiter\RateLimiterInterface;
use FreeElephants\JsonApiToolkit\RateLimiter\SkipRateLimitingSolver;
use FreeElephants\JsonApiToolkit\RequestIdentityResolver\RequestIdentityResolverInterface;

class RateLimitMiddlewareTest extends AbstractHttpTestCase
{
    public function testAttemptsCountingValid()
    {
        $request = $this->createServerRequest('GET', '/v1/foobars');
        $handler = $this->createRequestHandlerWithAssertions(
            function () {
                return $this->createResponse();
            }
        );

        $resolver = $this->createMock(RequestIdentityResolverInterface::class);
        $jarf = $this->createJsonApiResponseFactory();
        $rateLimiter = $this->createMock(RateLimiterInterface::class);
        $rateLimiter->method('limit')->willReturnCallback(fn ($identity, RateConfig $config) => $config->getLimitCount() - 1);
        $skipSolver = $this->createMock(SkipRateLimitingSolver::class);
        $skipSolver->method('isShouldSkip')->willReturn(false);

        $config = new RateConfig(2, 1);

        $middleware = new RateLimitMiddleware($jarf, $resolver, $config, $rateLimiter, $skipSolver);

        $rateLimiter->expects($this->once())->method('limit');
        $resolver->expects($this->once())->method('resolve');

        $response = $middleware->process($request, $handler);

        $this->assertEquals(
            1,
            (int) $response->getHeader(RateLimitMiddleware::HEADER_REMAINING)[0]
        );
    }

    public function testReturnsTooManyRequestsResponse()
    {
        $request = $this->createServerRequest('GET', '/v1/foobars');
        $handler = $this->createRequestHandlerWithAssertions(
            function () {
                return $this->createResponse();
            }
        );

        $resolver = $this->createMock(RequestIdentityResolverInterface::class);
        $jarf = $this->createJsonApiResponseFactory();
        $rateLimiter = $this->createMock(RateLimiterInterface::class);
        $rateLimiter->method('limit')->willReturnCallback(fn ($identity, RateConfig $config) => $config->getLimitCount() - 1);
        $skipSolver = $this->createMock(SkipRateLimitingSolver::class);
        $skipSolver->method('isShouldSkip')->willReturn(false);

        $config = new RateConfig(0, 1);

        $middleware = new RateLimitMiddleware($jarf, $resolver, $config, $rateLimiter, $skipSolver);
        $response = $middleware->process($request, $handler);

        $middleware->process($request, $handler);

        $this->assertEquals(
            429,
            $response->getStatusCode(),
        );
    }
}

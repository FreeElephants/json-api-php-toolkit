<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use FreeElephants\JsonApiToolkit\RateLimiter\RateConfig;
use FreeElephants\JsonApiToolkit\RateLimiter\RateLimiterInterface;
use FreeElephants\JsonApiToolkit\RateLimiter\SkipRateLimitingSolver;
use FreeElephants\JsonApiToolkit\RequestIdentityResolver\RequestIdentityResolverInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RateLimitMiddleware implements MiddlewareInterface
{
    public const HEADER_LIMIT = 'X-Rate-Limit';
    public const HEADER_REMAINING = 'X-Rate-Remaining';
    public const HEADER_TTL = 'X-Rate-Ttl';

    private JsonApiResponseFactory $responseFactory;
    private RequestIdentityResolverInterface $identityResolver;
    private RateConfig $rateConfig;
    private RateLimiterInterface $rateLimiter;
    private SkipRateLimitingSolver $skipRateLimitingSolver;

    public function __construct(
        JsonApiResponseFactory $jsonApiResponseFactory,
        RequestIdentityResolverInterface $identityResolver,
        RateConfig $rateConfig,
        RateLimiterInterface $rateLimiter,
        SkipRateLimitingSolver $skipRateLimitingSolver
    ) {
        $this->responseFactory = $jsonApiResponseFactory;
        $this->identityResolver = $identityResolver;
        $this->rateConfig = $rateConfig;
        $this->rateLimiter = $rateLimiter;
        $this->skipRateLimitingSolver = $skipRateLimitingSolver;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->skipRateLimitingSolver->isShouldSkip($request)) {
            $remaining = $this->rateLimiter->limit(
                $this->identityResolver->resolve($request),
                $this->rateConfig
            );
        } else {
            $remaining = 1;
        }

        if ($remaining <= 0) {
            return $this->responseFactory->createSingleErrorResponse('Rate Limit Exceed', 429, $request);
        }

        return $this->withHeaders($handler->handle($request), $remaining);
    }

    private function withHeaders(ResponseInterface $response, int $remaining): ResponseInterface
    {
        return $response->withHeader(self::HEADER_LIMIT, $this->rateConfig->getLimitCount())
            ->withHeader(self::HEADER_TTL, $this->rateConfig->getTtl())
            ->withHeader(self::HEADER_REMAINING, $remaining);
    }
}

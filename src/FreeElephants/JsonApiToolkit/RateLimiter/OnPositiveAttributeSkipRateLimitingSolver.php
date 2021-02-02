<?php

namespace FreeElephants\JsonApiToolkit\RateLimiter;

use Psr\Http\Message\ServerRequestInterface;

class OnPositiveAttributeSkipRateLimitingSolver implements SkipRateLimitingSolver
{
    private string $attributeName;

    public function __construct(string $attributeName)
    {
        $this->attributeName = $attributeName;
    }

    public function isShouldSkip(ServerRequestInterface $request): bool
    {
        return $request->getAttribute($this->attributeName, false);
    }
}

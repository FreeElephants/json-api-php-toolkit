<?php

namespace FreeElephants\JsonApiToolkit\RateLimiter;

use Psr\Http\Message\ServerRequestInterface;

interface SkipRateLimitingSolver
{
    public function isShouldSkip(ServerRequestInterface $request): bool;
}

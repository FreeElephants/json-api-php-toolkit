<?php

namespace FreeElephants\JsonApiToolkit\RateLimiter;

interface RateLimiterInterface
{
    /**
     * @return int remaining requests count
     */
    public function limit(string $identity, RateConfig $rateConfig): int;
}

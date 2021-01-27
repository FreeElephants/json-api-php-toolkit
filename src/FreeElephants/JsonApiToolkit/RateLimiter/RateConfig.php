<?php

namespace FreeElephants\JsonApiToolkit\RateLimiter;

class RateConfig
{
    private int $limitCount;
    private int $Ttl;

    public function __construct(int $limitCount, int $Ttl)
    {
        $this->limitCount = $limitCount;
        $this->Ttl = $Ttl;
    }

    public function getLimitCount(): int
    {
        return $this->limitCount;
    }

    public function getTtl(): int
    {
        return $this->Ttl;
    }
}

<?php

namespace FreeElephants\JsonApiToolkit\RateLimiter;

class RedisRateLimiter implements RateLimiterInterface
{
    private \Redis $redis;

    private const KEY = 'rate_limit:';

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function limit(string $identity, RateConfig $rateConfig): int
    {
        $this->redis->set($this->genKey($identity, true), 0, ['nx', 'ex' => $rateConfig->getTtl()]);

        return $rateConfig->getLimitCount() - $this->getValue($identity);
    }

    private function genKey(string $identity, $addTimestamp = false)
    {
        $result = self::KEY . $identity;

        if ($addTimestamp) {
            $result .= ':' . microtime(true);
        } else {
            $result .= ':*';
        }

        return $result;
    }

    private function getValue(string $identity): float
    {
        $count = 0;
        $iterator = null;

        while (
            ($keys = $this->redis->scan(
                $iterator,
                $this->genKey($identity)
            )) !== false
        ) {
            $count += count($keys);
        }

        return $count;
    }
}

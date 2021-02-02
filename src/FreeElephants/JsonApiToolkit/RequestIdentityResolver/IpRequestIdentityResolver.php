<?php

namespace FreeElephants\JsonApiToolkit\RequestIdentityResolver;

use Psr\Http\Message\ServerRequestInterface;

class IpRequestIdentityResolver implements RequestIdentityResolverInterface
{
    public function resolve(ServerRequestInterface $request): string
    {
        $serverParams = $request->getServerParams();

        if (array_key_exists('HTTP_CLIENT_IP', $serverParams)) {
            return $serverParams['HTTP_CLIENT_IP'];
        }

        if (array_key_exists('HTTP_X_FORWARDED_FOR', $serverParams)) {
            return $serverParams['HTTP_X_FORWARDED_FOR'];
        }

        return $serverParams['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}

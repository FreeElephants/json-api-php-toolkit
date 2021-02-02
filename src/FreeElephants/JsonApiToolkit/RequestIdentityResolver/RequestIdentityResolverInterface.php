<?php

namespace FreeElephants\JsonApiToolkit\RequestIdentityResolver;

use Psr\Http\Message\ServerRequestInterface;

interface RequestIdentityResolverInterface
{
    /**
     * @return string Identity
     *
     * @throws UnresolvableRequestIdentityException
     */
    public function resolve(ServerRequestInterface $request): string;
}

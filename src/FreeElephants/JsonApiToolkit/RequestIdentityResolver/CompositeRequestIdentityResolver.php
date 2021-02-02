<?php

namespace FreeElephants\JsonApiToolkit\RequestIdentityResolver;

use Psr\Http\Message\ServerRequestInterface;

class CompositeRequestIdentityResolver implements RequestIdentityResolverInterface
{
    private array $resolvers;

    public function __construct(RequestIdentityResolverInterface ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolve(ServerRequestInterface $request): string
    {
        foreach ($this->resolvers as $resolver) {
            try {
                return $resolver->resolve($request);
            } catch (UnresolvableRequestIdentityException $exception) {
                continue;
            }
        }
        throw new UnresolvableRequestIdentityException();
    }
}

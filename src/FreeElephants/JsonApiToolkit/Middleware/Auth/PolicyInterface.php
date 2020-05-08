<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Auth;

use Psr\Http\Message\ServerRequestInterface;

interface PolicyInterface
{
    public const RESULT_ALLOW = 0;
    public const RESULT_UNAUTHORIZED = 401;
    public const RESULT_FORBIDDEN = 403;

    public function check(ServerRequestInterface $request): int;
}

<?php

namespace FreeElephants\JsonApiToolkit\Psr;

use Psr\Http\Message\ServerRequestInterface;

trait AcceptLanguageNormalizer
{
    protected function normalizeAcceptLanguage(ServerRequestInterface $request): string
    {
        return \Locale::getPrimaryLanguage(\Locale::acceptFromHttp($request->getHeaderLine('Accept-Language')));
    }
}

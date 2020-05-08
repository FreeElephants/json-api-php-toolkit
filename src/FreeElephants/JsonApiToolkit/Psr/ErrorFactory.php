<?php

namespace FreeElephants\JsonApiToolkit\Psr;

use FreeElephants\I18n\DummyTranslatorRegistry;
use FreeElephants\I18n\TranslatorRegistryInterface;
use Neomerx\JsonApi\Schema\Error;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Throwable;

class ErrorFactory
{
    use AcceptLanguageNormalizer;

    private TranslatorRegistryInterface $translatorRegistry;

    public function __construct(TranslatorRegistryInterface $translatorRegistry = null)
    {
        $this->translatorRegistry = $translatorRegistry ?? new DummyTranslatorRegistry();
    }

    public function fromThrowable(Throwable $throwable, int $status, ServerRequestInterface $request, ?array $source = null): Error
    {
        $error = $this->createError($throwable->getMessage(), $status, $request, $source);
        $error->setCode($throwable->getCode());

        return $error;
    }

    public function createError(string $title, int $status, ServerRequestInterface $request, ?array $source = null): Error
    {
        $error = new Error(Uuid::uuid4()->toString());
        $error->setStatus($status);
        $error->setSource($source);
        $error->setTitle($this->translatorRegistry->getTranslator($this->normalizeAcceptLanguage($request))->translate($title));

        return $error;
    }
}

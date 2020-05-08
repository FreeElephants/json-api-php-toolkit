<?php

namespace FreeElephants\JsonApiToolkit\Psr;

use Exception;
use FreeElephants\I18n\Translator;
use FreeElephants\I18n\TranslatorRegistryInterface;
use FreeElephants\JsonApiToolkit\AbstractTestCase;
use Psr\Http\Message\ServerRequestInterface;

class ErrorFactoryTest extends AbstractTestCase
{
    public function testFromThrowable()
    {
        $translator = $this->createMock(Translator::class);
        $translator->method('translate')->with('Hello, world!')->willReturn('Привет, мир!');
        $translatorRegistry = $this->createMock(TranslatorRegistryInterface::class);
        $translatorRegistry->method('getTranslator')->willReturn($translator);

        $throwable = new Exception('Hello, world!', 100500);
        $request = $this->createMock(ServerRequestInterface::class);

        $errorFactory = new ErrorFactory($translatorRegistry);
        $error = $errorFactory->fromThrowable($throwable, 200, $request, ['pointer']);

        self::assertEquals('Привет, мир!', $error->getTitle());
        self::assertEquals(100500, $error->getCode());
        self::assertEquals(200, $error->getStatus());
        self::assertEquals(['pointer'], $error->getSource());
    }

    public function testCreateError()
    {
        $translator = $this->createMock(Translator::class);
        $translator->method('translate')->with('Hello, world!')->willReturn('Привет, мир!');
        $translatorRegistry = $this->createMock(TranslatorRegistryInterface::class);
        $translatorRegistry->method('getTranslator')->willReturn($translator);

        $request = $this->createMock(ServerRequestInterface::class);

        $errorFactory = new ErrorFactory($translatorRegistry);
        $error = $errorFactory->createError('Hello, world!', 200, $request, ['pointer']);

        self::assertEquals('Привет, мир!', $error->getTitle());
        self::assertEquals(null, $error->getCode());
        self::assertEquals(200, $error->getStatus());
        self::assertEquals(['pointer'], $error->getSource());
    }
}

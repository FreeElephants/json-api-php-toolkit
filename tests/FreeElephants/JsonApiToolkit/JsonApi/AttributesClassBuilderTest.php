<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use FreeElephants\JsonApiToolkit\AbstractTestCase;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\YamlFileParser;
use Nette\PhpGenerator\PsrPrinter;

class AttributesClassBuilderTest extends AbstractTestCase
{

    public function testCollect()
    {
        $openapi = (new YamlFileParser())->parse(self::FIXTERE_PATH . '/json-api-simple-attributes-mapping-example.yml');
        $collector = new AttributesClassBuilder();
        $attributesClass = $collector->buildClass($openapi, 'articles');
        $expectedAttributesSourceCode = <<<PHP
final class ArticlesAttributes
{
    public string \$title;
}

PHP;
        $this->assertSame($expectedAttributesSourceCode, (new PsrPrinter())->printClass($attributesClass));

    }
}
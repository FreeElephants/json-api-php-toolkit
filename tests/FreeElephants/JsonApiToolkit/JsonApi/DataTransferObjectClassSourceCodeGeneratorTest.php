<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use FreeElephants\JsonApiToolkit\AbstractTestCase;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\YamlFileParser;

class DataTransferObjectClassSourceCodeGeneratorTest extends AbstractTestCase
{

    public function testGenerate()
    {
        $generator = new DataTransferObjectClassSourceCodeGenerator();
        $openapi = (new YamlFileParser())->parse(self::FIXTERE_PATH . '/json-api.yml');

        $set = $generator->generate($openapi, 'articles');
        $expectedDocumentSourceCode = <<<PHP
final class ArticlesDocument
{
    public ArticlesResourceObject \$data;
}

PHP;
        $this->assertSame($expectedDocumentSourceCode, $set->getDocumentSourceCode());

        $expectedResourceObjectSourceCode = <<<PHP
final class ArticlesResourceObject
{
    public string \$id;

    public string \$type;

    public ArticlesAttributes \$attributes;
}

PHP;
        $this->assertSame($expectedResourceObjectSourceCode, $set->getResourceObjectSourceCode());

        $expectedAttributesSourceCode = <<<PHP
final class ArticlesAttributes
{
    public string \$title;
}

PHP;

        $this->assertSame($expectedAttributesSourceCode, $set->getAttributesObjectSourceCode());
    }
}
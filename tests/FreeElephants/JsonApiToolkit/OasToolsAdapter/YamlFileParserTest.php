<?php

namespace FreeElephants\JsonApiToolkit\OasToolsAdapter;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class YamlFileParserTest extends AbstractTestCase
{

    public function testParse()
    {
        $parser = new YamlFileParser();
        $openapi = $parser->parse(self::FIXTURE_PATH . '/petstore.yaml');
        $this->assertSame('Swagger Petstore', $openapi->info->title);
    }
}

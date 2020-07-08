<?php

namespace FreeElephants\JsonApiToolkit\OasToolsAdapter;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class JsonFileParserTest extends AbstractTestCase
{

    public function testParse()
    {
        $parser = new JsonFileParser();
        $openapi = $parser->parse(self::FIXTERE_PATH . '/petstore.json');
        $this->assertSame('Swagger Petstore', $openapi->info->title);
    }
}

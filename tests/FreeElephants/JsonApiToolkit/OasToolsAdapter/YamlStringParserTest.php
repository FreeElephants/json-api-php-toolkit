<?php

namespace FreeElephants\JsonApiToolkit\OasToolsAdapter;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class YamlStringParserTest extends AbstractTestCase
{

    public function testParse()
    {
        $parser = new YamlStringParser();
        $source = <<<YAML
openapi: "3.0.0"
info:
  version: 1.0.0
YAML;

        $openapi = $parser->parse($source);
        $this->assertSame('3.0.0', $openapi->openapi);
        $this->assertSame('1.0.0', $openapi->info->version);
        $this->assertEmpty($openapi->paths);
    }
}
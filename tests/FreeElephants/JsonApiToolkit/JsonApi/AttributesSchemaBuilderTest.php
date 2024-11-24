<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use FreeElephants\JsonApiToolkit\AbstractTestCase;
use FreeElephants\JsonApiToolkit\OasToolsAdapter\YamlFileParser;

class AttributesSchemaBuilderTest extends AbstractTestCase
{

    public function testBuild()
    {
        $openapi = (new YamlFileParser())->parse(self::FIXTURE_PATH . '/json-api-simple-attributes-mapping-example.yml');
        $attributesSchemaBuilder = new AttributesSchemaBuilder();
        $schema = $attributesSchemaBuilder->build($openapi, 'articles');
        $this->assertTrue($schema->hasAttribute('title'));
        $this->assertSame('string', $schema->getAttribute('title')->getType());
    }

    public function testBuildFromAllOf()
    {
        $this->markTestIncomplete();
        $openapi = (new YamlFileParser())->parse(self::FIXTURE_PATH . '/json-api.yml');
        $attributesSchemaBuilder = new AttributesSchemaBuilder();
        $schema = $attributesSchemaBuilder->build($openapi, 'people');
        $this->assertTrue($schema->hasAttribute('first-name'));
        $this->assertTrue($schema->hasAttribute('last-name'));
        $this->assertTrue($schema->hasAttribute('twitter'));
    }
}

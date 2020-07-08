<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class AttributesSchemaTest extends AbstractTestCase
{

    public function testAddAttribute()
    {
        $schema = new AttributesSchema();
        $schema->addAttribute(new Attribute('title', 'string',));
        $this->assertCount(1, $schema);
        $this->assertTrue($schema->hasAttribute('title'));
    }

}

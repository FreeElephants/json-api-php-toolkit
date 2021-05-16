<?php

namespace FreeElephants\JsonApiToolkit\DTO;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class ResourceObjectTest extends AbstractTestCase
{
    public function testRelationshipTypes()
    {
        $resourceObject = new class([
            'id'            => 'id',
            'type'          => 'type',
            'attributes'    => [
                'foo' => 'bar',
            ],
            'relationships' => [
                'one' => [
                    'data' => [
                        'type' => 'one',
                        'id'   => 'one',
                    ],
                ],
            ],
        ]) extends AbstractResourceObject {
            public Attributes $attributes;
            public OneRelationships $relationships;
        };

        $this->assertInstanceOf(OneRelationships::class, $resourceObject->relationships);
        $this->assertSame('one', $resourceObject->relationships->one->data->type);
    }
}

class Attributes extends AbstractAttributes
{
    public string $foo;
}

class OneRelationships extends AbstractRelationships
{
    public RelationshipToOne $one;
}


class TwoRelationships extends AbstractRelationships
{
    public RelationshipToOne $two;
}


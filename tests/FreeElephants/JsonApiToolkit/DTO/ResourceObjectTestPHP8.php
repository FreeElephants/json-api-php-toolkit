<?php

namespace FreeElephants\JsonApiToolkit\DTO;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class ResourceObjectTestPHP8 extends AbstractTestCase
{
    public function testUnionTypes()
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
        ]) extends AbstractResourceObject{
            public Attributes $attributes;
            public OneRelationships|TwoRelationships $relationships;
        };

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


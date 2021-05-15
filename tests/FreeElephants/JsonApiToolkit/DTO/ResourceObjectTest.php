<?php

namespace FreeElephants\JsonApiToolkit\DTO;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class ResourceObjectTest extends AbstractTestCase
{
    public function testUnionTypes()
    {
        $resourceObject = new ResourceWithUnionTypedProps([
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
        ]);

        $this->assertSame('one', $resourceObject->relationships->one->data->type);
    }
}


class ResourceWithUnionTypedProps extends AbstractResourceObject
{
    public Attributes $attributes;
    public OneRelationships|TwoRelationships $relationships;
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

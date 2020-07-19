<?php

namespace FreeElephants\JsonApiToolkit\DTO;

use FreeElephants\JsonApiToolkit\AbstractTestCase;
use Nyholm\Psr7\ServerRequest;

class DocumentTest extends AbstractTestCase
{

    public function testFromRequest()
    {
        $request = new ServerRequest('POST', '/foo');
        $request->getBody()->write(<<<JSON
{
    "data": {
        "id": "123",
        "type": "foo",
        "attributes": {
            "foo": "bar",
            "date": "2012-04-23T18:25:43.511Z",
            "nested": {
                "someNestedStructure": {
                    "someKey": "someValue"
                }
            }
        }
    }
}
JSON
        );

        $fooDTO = FooDocument::fromHttpMessage($request);

        $this->assertInstanceOf(FooResource::class, $fooDTO->data);
        $this->assertInstanceOf(FooAttributes::class, $fooDTO->data->attributes);
        $this->assertSame('foo', $fooDTO->data->type);
        $this->assertSame('bar', $fooDTO->data->attributes->foo);
        $this->assertEquals(new \DateTime('2012-04-23T18:25:43.511Z'), $fooDTO->data->attributes->date);
        $this->assertEquals('someValue', $fooDTO->data->attributes->nested->someNestedStructure->someKey);
    }
}

class FooDocument extends AbstractDocument
{
    public FooResource $data;
}

class FooResource extends AbstractResourceObject
{
    public FooAttributes $attributes;
}

class FooAttributes extends AbstractAttributes
{
    public string $foo;
    public \DateTime $date;
    public Nested $nested;
}

class Nested extends BaseKeyValueStructure
{
    public SomeNestedStructure $someNestedStructure;
}

class SomeNestedStructure extends BaseKeyValueStructure
{
    public string $someKey;
}

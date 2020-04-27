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
            "foo": "bar"
        }
    }
}
JSON
        );

        $fooDTO = FooDocument::fromRequest($request);

        $this->assertInstanceOf(FooResource::class, $fooDTO->data);
        $this->assertInstanceOf(FooAttributes::class, $fooDTO->data->attributes);
        $this->assertSame('bar', $fooDTO->data->attributes->foo);
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
}

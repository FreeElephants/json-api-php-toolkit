<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use FreeElephants\JsonApiToolkit\AbstractHttpTestCase;
use Psr\Http\Message\ServerRequestInterface;

class BodyParserTest extends AbstractHttpTestCase
{
    public function testProcess()
    {
        $request = $this->createServerRequest('POST', '/foo');
        $request->getBody()->write(<<<JSON
{
    "data": {
        "id": "foo",
        "type": "bar"
    }
}
JSON
        );
        $handler = $this->createRequestHandlerWithAssertions(function (ServerRequestInterface $request) {
            $this->assertSame([
                'data' => [
                    'id'   => 'foo',
                    'type' => 'bar',
                ],
            ], $request->getParsedBody());

            return $this->createResponse();
        });

        $bodyParser = new BodyParser();
        $bodyParser->process($request, $handler);
    }
}

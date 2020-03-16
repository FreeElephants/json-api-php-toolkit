<?php

namespace FreeElephants\JsonApiToolkit\Routing\OpenApi;

use PHPUnit\Framework\TestCase;

class JsonRoutesMapCallableHandlerBuilderTest extends TestCase
{

    public function testBuildRoutesMap()
    {
        $builder = new JsonRoutesMapCallableHandlerBuilder();
        $jsonSpec = <<<JSON

JSON;
        $expectedRouteMap = [];
        $this->assertEquals($expectedRouteMap, $builder->buildRoutesMap($jsonSpec));
    }
}
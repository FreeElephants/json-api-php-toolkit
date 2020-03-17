<?php

namespace FreeElephants\JsonApiToolkit\Routing\OpenApi;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class JsonRoutesMapCallableHandlerBuilderTest extends AbstractTestCase
{

    public function testBuildRoutesMap()
    {
        $builder = new JsonRoutesMapCallableHandlerBuilder();
        $jsonSpec = file_get_contents(self::FIXTERE_PATH . '/examples/v2.0/json/petstore-with-callable-operationIds.json');
        $expectedRouteMap = [
            '/pets'         => [
                'GET'  => 'listPets',
                'POST' => 'createPets',
            ],
            '/pets/{petId}' => [
                'GET' => 'showPetById',
            ]
        ];
        $this->assertEquals($expectedRouteMap, $builder->buildRoutesMap($jsonSpec));
    }
}
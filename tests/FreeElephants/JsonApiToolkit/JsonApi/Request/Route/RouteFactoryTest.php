<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RouteFactoryTest extends TestCase
{
    /**
     * @dataProvider correctDataProvider
     */
    public function testCreateCorrect(string $path, RouteRulesProvider $routeRulesProvider, string $expected)
    {
        $factory = new RouteFactory($this->getMappingProvider(), $routeRulesProvider);
        $actual = $factory->create($path);
        $this->assertTrue(is_a($actual, $expected));
    }

    public static function correctDataProvider(): array
    {
        $routeRulesProviderWithoutApiVersion = self::getRouteRulesProviderWithoutApiVersion();
        $routeRulesProviderWithApiVersion = self::getRouteRulesProviderWithApiVersion();
        $uuid = Uuid::uuid4()->toString();

        return [
            ['/foo', $routeRulesProviderWithoutApiVersion, ResourcesCollectionRoute::class],
            ["/foo/$uuid", $routeRulesProviderWithoutApiVersion, ResourceRoute::class],
            ["/foo/$uuid/relationships/bar", $routeRulesProviderWithoutApiVersion, RelationshipRoute::class],
            ["/foo/$uuid/baz", $routeRulesProviderWithoutApiVersion, MemberRoute::class],
            ['/v1/foo', $routeRulesProviderWithApiVersion, ResourcesCollectionRoute::class],
            ["/v1/foo/$uuid", $routeRulesProviderWithApiVersion, ResourceRoute::class],
            ["/v1/foo/$uuid/relationships/bar", $routeRulesProviderWithApiVersion, RelationshipRoute::class],
            ["/v1/foo/$uuid/baz", $routeRulesProviderWithApiVersion, MemberRoute::class],
        ];
    }

    /**
     * @dataProvider incorrectDataProvider
     */
    public function testCreateIncorrect(string $path, RouteRulesProvider $routeRulesProvider)
    {
        $factory = new RouteFactory($this->getMappingProvider(), $routeRulesProvider);

        $this->expectException(InvalidArgumentException::class);

        $factory->create($path);
    }

    public static function incorrectDataProvider(): array
    {
        $routeRulesProviderWithoutApiVersion = self::getRouteRulesProviderWithoutApiVersion();
        $routeRulesProviderWithApiVersion = self::getRouteRulesProviderWithApiVersion();
        $uuid = Uuid::uuid4()->toString();

        return [
            ['', $routeRulesProviderWithoutApiVersion],
            ['/v1/foo', $routeRulesProviderWithoutApiVersion],
            ['foo', $routeRulesProviderWithoutApiVersion],
            ['/zor', $routeRulesProviderWithoutApiVersion],
            ['/Foo', $routeRulesProviderWithoutApiVersion],
            ['/foo/bar', $routeRulesProviderWithoutApiVersion],
            ["/foo/$uuid/relationship/bar", $routeRulesProviderWithoutApiVersion],
            ["/foo/$uuid/relationships", $routeRulesProviderWithoutApiVersion],
            ["/foo/$uuid/relationships/zor", $routeRulesProviderWithoutApiVersion],
            ["/foo/$uuid/zor", $routeRulesProviderWithoutApiVersion],
            ['', $routeRulesProviderWithApiVersion],
            ['v1/foo', $routeRulesProviderWithApiVersion],
            ['/foo', $routeRulesProviderWithApiVersion],
            ['/v1/Foo', $routeRulesProviderWithApiVersion],
            ['/v1/zor', $routeRulesProviderWithApiVersion],
            ["/v1/foo/$uuid/relationship/bar", $routeRulesProviderWithApiVersion],
            ["/v1/foo/$uuid/relationships", $routeRulesProviderWithApiVersion],
            ["/v1/foo/$uuid/zor", $routeRulesProviderWithApiVersion],
        ];
    }

    private function getMappingProvider(): MappingConfigProvider
    {
        return new MappingConfigProvider(
            [
                MappingConfigProvider::ENDPOINT_TYPE_TO_DOCUMENT_TYPE => [
                    'foo' => 'FOO',
                    'Foo' => 'FOO',
                    'bar' => 'BAR',
                    'baz' => 'BAZ',
                ],
            ]
        );
    }

    private static function getRouteRulesProviderWithoutApiVersion(): RouteRulesProvider
    {
        $resourceRegex = '([a-z][A-Za-z\d]+)';
        $hexRegex = '[\da-f]';
        $uuidRegex = "($hexRegex{8}-$hexRegex{4}-$hexRegex{4}-$hexRegex{4}-$hexRegex{12})";

        return new RouteRulesProvider(
            [
                RouteInterface::TYPE_RESOURCES_COLLECTION => sprintf('/^\/%s$/', $resourceRegex),
                RouteInterface::TYPE_RESOURCE => sprintf('/^\/%s\/%s$/', $resourceRegex, $uuidRegex),
                RouteInterface::TYPE_RELATIONSHIP => sprintf('/^\/%s\/%s\/relationships\/%s$/', $resourceRegex, $uuidRegex, $resourceRegex),
                RouteInterface::TYPE_MEMBER => sprintf('/^\/%s\/%s\/%s$/', $resourceRegex, $uuidRegex, $resourceRegex),
            ],
            false
        );
    }

    private static function getRouteRulesProviderWithApiVersion(): RouteRulesProvider
    {
        $versionRegex = '(v\d+)';
        $resourceRegex = '([a-z][A-Za-z\d]+)';
        $hexRegex = '[\da-f]';
        $uuidRegex = "($hexRegex{8}-$hexRegex{4}-$hexRegex{4}-$hexRegex{4}-$hexRegex{12})";

        return new RouteRulesProvider(
            [
                RouteInterface::TYPE_RESOURCES_COLLECTION => sprintf('/^\/%s\/%s$/', $versionRegex, $resourceRegex),
                RouteInterface::TYPE_RESOURCE => sprintf('/^\/%s\/%s\/%s$/', $versionRegex, $resourceRegex, $uuidRegex),
                RouteInterface::TYPE_RELATIONSHIP => sprintf('/^\/%s\/%s\/%s\/relationships\/%s$/', $versionRegex, $resourceRegex, $uuidRegex, $resourceRegex),
                RouteInterface::TYPE_MEMBER => sprintf('/^\/%s\/%s\/%s\/%s$/', $versionRegex, $resourceRegex, $uuidRegex, $resourceRegex),
            ],
            true
        );
    }
}

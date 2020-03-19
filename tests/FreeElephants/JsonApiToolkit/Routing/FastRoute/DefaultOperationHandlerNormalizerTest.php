<?php

namespace FreeElephants\JsonApiToolkit\Routing\FastRoute;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class DefaultOperationHandlerNormalizerTest extends AbstractTestCase
{

    public function testNormalize()
    {
        $normalizer = new DefaultOperationHandlerNormalizer();
        $this->assertSame('foo', $normalizer->normalize('foo'));
    }
}
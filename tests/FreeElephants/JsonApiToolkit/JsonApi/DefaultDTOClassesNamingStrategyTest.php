<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use FreeElephants\JsonApiToolkit\AbstractTestCase;

class DefaultDTOClassesNamingStrategyTest extends AbstractTestCase
{

    public function testBuildAttributesClassName()
    {
        $namingStrategy = new DefaultDTOClassesNamingStrategy();
        $this->assertSame('ArticlesAttributes', $namingStrategy->buildAttributesClassName('articles'));
    }

    public function testBuildDocumentClassName()
    {
        $namingStrategy = new DefaultDTOClassesNamingStrategy();
        $this->assertSame('ArticlesDocument', $namingStrategy->buildDocumentClassName('articles'));
    }

    public function testBuildResourceObjectClassName()
    {
        $namingStrategy = new DefaultDTOClassesNamingStrategy();
        $this->assertSame('ArticlesResourceObject', $namingStrategy->buildResourceObjectClassName('articles'));
    }

}

<?php

namespace FreeElephants\JsonApiToolkit\OasToolsAdapter;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;

class YamlFileParser implements OpenApiDocumentParserInterface
{

    public function parse(string $source): OpenApi
    {
        return Reader::readFromYamlFile($source);
    }
}
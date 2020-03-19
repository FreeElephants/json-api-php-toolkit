<?php

namespace FreeElephants\JsonApiToolkit\OasToolsAdapter;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;

class YamlStringParser implements OpenApiDocumentParserInterface
{

    public function parse(string $source): OpenApi
    {
        return Reader::readFromYaml($source);
    }
}
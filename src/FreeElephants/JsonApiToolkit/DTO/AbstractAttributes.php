<?php

namespace FreeElephants\JsonApiToolkit\DTO;

abstract class AbstractAttributes
{
    public function __construct(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->{$name} = $value;
        }
    }
}

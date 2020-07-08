<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

class AttributesSchema implements \Countable
{

    private \ArrayObject $attributes;

    public function __construct()
    {
        $this->attributes = new \ArrayObject();
    }

    public function addAttribute(Attribute $attribute)
    {
        $this->attributes->offsetSet($attribute->getName(), $attribute);
    }

    /**
     * @return iterable|Attribute[]
     */
    public function getAttributes(): iterable
    {
        return $this->attributes->getIterator();
    }

    public function count(): int
    {
        return $this->attributes->count();
    }

    public function hasAttribute(string $name): bool
    {
        return $this->attributes->offsetExists($name);
    }

    public function getAttribute(string $name): Attribute
    {
        if ($this->attributes->offsetExists($name)) {
            return $this->attributes->offsetGet($name);
        }

        $message = sprintf('Attribute with name %s not found in schema', $name);
        throw new \OutOfBoundsException($message);
    }
}

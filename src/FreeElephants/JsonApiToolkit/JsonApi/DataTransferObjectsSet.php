<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PsrPrinter;

class DataTransferObjectsSet
{

    private ClassType $document;
    private ClassType $data;
    private ClassType $attributes;
    private PsrPrinter $printer;

    public function __construct(ClassType $document, ClassType $data, ClassType $attributes)
    {
        $this->document = $document;
        $this->data = $data;
        $this->attributes = $attributes;
        $this->printer = new PsrPrinter();
    }

    public function getDocumentSourceCode(): string
    {
        return $this->printer->printClass($this->document);
    }

    public function getResourceObjectSourceCode(): string
    {
        return $this->printer->printClass($this->data);
    }

    public function getAttributesObjectSourceCode(): string
    {
        return $this->printer->printClass($this->attributes);
    }
}

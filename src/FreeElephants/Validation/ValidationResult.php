<?php

namespace FreeElephants\Validation;

class ValidationResult
{
    private array $errors = [];

    public function addError(string $key, string $ruleName, string $message)
    {
        $this->errors[$key][$ruleName] = $message;
    }

    public function valid(): bool
    {
        return 0 === count($this->errors);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

<?php

namespace FreeElephants\Validation;

interface ValidatorInterface
{
    public function validate(array $data, Rules $rules, string $language = null): ValidationResult;
}

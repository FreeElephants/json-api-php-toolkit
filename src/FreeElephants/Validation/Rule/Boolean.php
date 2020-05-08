<?php

namespace FreeElephants\Validation\Rule;

use Rakit\Validation\Rule;

class Boolean extends Rule
{
    public function check($value): bool
    {
        return is_bool($value);
    }
}

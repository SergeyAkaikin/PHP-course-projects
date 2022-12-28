<?php

namespace MusicService\Http\Validators;

class IntValidator implements IValidator
{

    public function isValid(string $fieldName, array $params): bool
    {
        $field = $params[$fieldName];
        if (!is_numeric($field)) {
            return false;
        }

        $int = (int)$field;
        $float = (float)$field;

        return $int == $float;
    }
}
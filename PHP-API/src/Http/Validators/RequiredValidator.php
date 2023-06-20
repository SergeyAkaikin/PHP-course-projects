<?php

namespace MusicService\Http\Validators;

class RequiredValidator implements IValidator
{

    public function isValid(string $fieldName, array $params): bool
    {
        if (!key_exists($fieldName, $params)) {
            return false;
        }

        return $params[$fieldName] !== null;
    }
}
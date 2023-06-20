<?php

namespace MusicService\Http\Validators;

use MusicService\Utils\UserUtils;

class DateValidator implements IValidator
{

    public function isValid(string $fieldName, array $params): bool
    {
        $field = $params[$fieldName];
        return UserUtils::isAbleDate($field);
    }
}
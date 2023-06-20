<?php
declare(strict_types=1);

namespace MusicService\Http\Validators;

interface IValidator
{
    public function isValid(string $fieldName, array $params): bool;
}
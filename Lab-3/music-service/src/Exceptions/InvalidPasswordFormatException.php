<?php

namespace MusicService\Exceptions;


class InvalidPasswordFormatException extends \Exception
{
    public function __construct(string $message = "Invalid password format", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
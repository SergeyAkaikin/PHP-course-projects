<?php

namespace MusicService\Exceptions;



class InvalidEmailFormatException extends \Exception
{

    public function __construct(string $message = "Used invalid email format", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
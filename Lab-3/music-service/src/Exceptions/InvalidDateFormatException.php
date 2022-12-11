<?php

namespace MusicService\Exceptions;



class InvalidDateFormatException extends \Exception
{
    public function __construct(string $message = "Used invalid format of date string", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
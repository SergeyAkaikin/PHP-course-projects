<?php

namespace MusicService\Exceptions;


use Exception;
use Throwable;

class InvalidPasswordFormatException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Invalid password format", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
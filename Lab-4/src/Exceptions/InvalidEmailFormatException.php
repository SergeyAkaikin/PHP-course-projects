<?php

namespace MusicService\Exceptions;


use Exception;
use Throwable;

class InvalidEmailFormatException extends Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Used invalid email format", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
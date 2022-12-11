<?php

namespace MusicService\Exceptions;



class InvalidDateFormatException extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "Used invalid format of date string", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
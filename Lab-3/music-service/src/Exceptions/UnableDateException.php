<?php

namespace MusicService\Exceptions;



class UnableDateException extends \Exception
{
    public function __construct(string $message = "Used date later than today", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
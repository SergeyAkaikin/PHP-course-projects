<?php

namespace MusicService\Http;

use MusicService\Api\Status;

class Response
{
    public function __construct(
        public Status $status,
        public mixed $data,
        public ?string $message = null
    )
    {
    }
}
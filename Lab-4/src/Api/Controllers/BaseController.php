<?php

namespace MusicService\Api\Controllers;

use MusicService\Api\Status;
use MusicService\Http\Response;

abstract class BaseController
{
    public function successResponse(mixed $data): Response
    {
        return new Response(Status::Ok, $data, null);
    }

    public function failResponse(Status $status, string $message, mixed $data = null): Response {
        return new Response($status, $data, $message);
    }
}
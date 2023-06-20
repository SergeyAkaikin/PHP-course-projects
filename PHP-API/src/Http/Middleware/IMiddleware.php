<?php

namespace MusicService\Http\Middleware;

use MusicService\Http\Request;
use MusicService\Http\Response;

interface IMiddleware
{
    /**
     * @param callable(Request): Response $next
     */
    public function handle(Request $request, callable $next): Response;
}
<?php
declare(strict_types=1);
namespace MusicService\Http\Middleware;

use MusicService\Api\Status;
use MusicService\Http\Request;
use MusicService\Http\Response;

class ValidationMiddleware implements IMiddleware
{

    /**
     * @inheritDoc
     */
    public function handle(Request $request, callable $next): Response
    {
        if (!$request->validate()) {
            return new Response(Status::InvalidRequest, $request->validationMessages);
        }

        return $next($request);
    }
}
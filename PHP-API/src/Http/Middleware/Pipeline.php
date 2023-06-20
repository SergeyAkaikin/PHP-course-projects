<?php
declare(strict_types=1);

namespace MusicService\Http\Middleware;

use MusicService\Http\Request;
use MusicService\Http\Response;

class Pipeline
{

    /**
     * @param callable(Request): Response $handler
     * @param list<IMiddleware> $middlewares
     */
    public function __construct(
        private $handler,
        private array $middlewares
    )
    {
    }


    public function handle(Request $request): Response
    {

        $middleware = array_shift($this->middlewares);

        if ($middleware !== null) {
            return $middleware->handle($request, [$this, 'handle']);
        }

        return ($this->handler)($request);
    }
}
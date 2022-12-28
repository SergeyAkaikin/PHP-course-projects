<?php

namespace MusicService\Http\Routing;

class Router
{
    /**
     * @var Route[] $routes
     */
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function findRequestHandler(string $method, string $path): ?callable
    {
        foreach ($this->routes as $route) {
            if ($route->canHandle($method, $path)) {
                return $route->getHandle();
            }
        }
        return null;
    }
}
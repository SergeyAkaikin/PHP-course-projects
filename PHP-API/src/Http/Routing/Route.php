<?php
declare(strict_types=1);

namespace MusicService\Http\Routing;

use MusicService\Http\Request;
use MusicService\Http\Response;

class Route
{
    /**
     * @var callable $action
     */
    private $action;

    private function __construct(private readonly string $method, private readonly string $path, callable $action)
    {
        $this->action = $action;
    }

    public static function create(string $method, string $path, callable $action): Route
    {
        return new self($method, $path, $action);
    }

    public static function createGet(string $path, callable $action): Route
    {
        return new self('GET', $path, $action);
    }

    public static function createPost(string $path, callable $action): Route
    {
        return new self('POST', $path, $action);
    }

    public static function createPut(string $path, callable $action): Route
    {
        return new self('PUT', $path, $action);
    }

    public static function createDelete(string $path, callable $action): Route
    {
        return new self('DELETE', $path, $action);
    }

    public function canHandle(string $method, string $path): bool
    {
        return $this->method === $method && $this->path === $path;
    }

    /**
     * @return callable(Request): Response
     */
    public function getHandle(): callable
    {
        return $this->action;
    }
}
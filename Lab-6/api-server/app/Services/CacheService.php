<?php
declare(strict_types=1);

namespace App\Services;

use App\Utils\RedisConnection;

class CacheService
{
    public function getOrAdd(string $key, ?callable $callback = null, ?int $ttl = null): mixed
    {
        $connection = RedisConnection::appCache();
        $item = $connection->get($key);

        if ($item !== null) {
            return json_decode($item);
        }

        if ($callback !== null) {
            $item = $callback();
            if ($item !== null) {
                $connection->set($key, json_encode($item), 'EX', $ttl);
            }
        }

        return $item;

    }

    public function delete(string $key): void
    {
        RedisConnection::appCache()->del($key);
    }

    public function exists(string $key): int
    {
        return RedisConnection::appCache()->exists($key);
    }

}

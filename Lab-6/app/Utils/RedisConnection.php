<?php
declare(strict_types=1);

namespace App\Utils;


use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\Facades\Redis;

class RedisConnection
{
    public static function appCache(): ?PredisConnection
    {
        return self::toPredisConnection(Redis::connection('app-cache'));
    }


    private static function toPredisConnection(Connection $connection): ?PredisConnection
    {
        if ($connection instanceof PredisConnection) {
            return $connection;
        }

        return null;
    }
}

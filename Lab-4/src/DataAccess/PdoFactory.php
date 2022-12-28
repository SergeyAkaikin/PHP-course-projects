<?php
declare(strict_types=1);

namespace MusicService\DataAccess;

use PDO;

class PdoFactory
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        self::$pdo = new PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_SCHEMA']}",
            "{$_ENV['DB_USERNAME']}",
            "{$_ENV['DB_PASSWORD']}",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]
        );

        return self::$pdo;
    }
}
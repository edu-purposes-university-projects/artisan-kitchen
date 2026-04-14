<?php

declare(strict_types=1);

namespace App;

final class Database
{
    private static $connection = null;

    /** @return resource|null */
    public static function connection()
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $dbHost = getenv('DB_HOST') ?: 'localhost';
        $dbPort = getenv('DB_PORT') ?: '5432';
        $dbName = getenv('DB_NAME') ?: 'baltaci_kitchen';
        $dbUser = getenv('DB_USER') ?: 'baltaci_user';
        $dbPassword = getenv('DB_PASSWORD') ?: 'baltaci_password';

        $connectionString = sprintf(
            'host=%s port=%s dbname=%s user=%s password=%s',
            $dbHost,
            $dbPort,
            $dbName,
            $dbUser,
            $dbPassword
        );

        $db = @pg_connect($connectionString);
        self::$connection = $db ?: null;

        return self::$connection;
    }
}

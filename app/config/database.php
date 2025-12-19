<?php

class Database
{
    private static $pdo;

    public static function getConnection(): PDO
    {
        if (self::$pdo) {
            return self::$pdo;
        }

        $host = env('DB_HOST', 'localhost');
        $db = env('DB_NAME', 'dz7_manager');
        $user = env('DB_USER', 'root');
        $pass = env('DB_PASS', '');
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        self::$pdo = new PDO($dsn, $user, $pass, $options);
        return self::$pdo;
    }
}

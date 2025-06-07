<?php

namespace Core;

use Exception;
use PDO;
use PDOException;

class DatabaseConnection
{
    private static ?PDO $pdo = null;

    protected function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $host = getenv('POSTGRES_HOST') ?: 'postgres';
            $port = getenv('POSTGRES_PORT') ?: '5432';
            $dbname = getenv('POSTGRES_DB') ?: 'db';
            $user = getenv('POSTGRES_USER') ?: 'user';
            $password = getenv('POSTGRES_PASSWORD') ?: 'user';

            try {
                self::$pdo = new PDO(
                    "pgsql:host=$host;port=$port;dbname=$dbname",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
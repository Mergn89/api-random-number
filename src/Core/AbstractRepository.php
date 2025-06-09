<?php

namespace Core;

use Exception;
use PDO;
use PDOException;

abstract class AbstractRepository
{
    private static ?PDO $pdo = null;
    protected string $entityClass;
    protected array $fillable = [];

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

    public function save(object $entity): object
    {
        $pdo = $this->getConnection();
        $data = [];
        foreach ($this->fillable as $field) {
            $getter = 'get' . ucfirst($field);
            if (method_exists($entity, $getter)) {
                $data[$field] = $entity->$getter();
            }
        }

        try {
            if ($entity->getId() === null) {
                $columns = implode(', ', array_keys($data));
                $placeholders = ':' . implode(', :', array_keys($data));
                $stmt = $pdo->prepare("INSERT INTO {$this->getTableName()} ($columns) VALUES ($placeholders)");
                $stmt->execute($data);
                $id = $pdo->lastInsertId();
                return $this->find($id) ?? $entity;
            } else {
                $columns = array_keys($data);
                $setClause = implode(', ', array_map(fn($col) => "$col = :$col", $columns));
                $stmt = $pdo->prepare("UPDATE {$this->getTableName()} SET $setClause WHERE id = :id");
                $stmt->execute(array_merge($data, ['id' => $entity->getId()]));
                return $this->find($entity->getId()) ?? $entity;
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to save entity: " . $e->getMessage());
        }
    }

    public function find(string $id): ?object
    {
        $pdo = $this->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new $this->entityClass(...$result);
    }

    abstract protected function getTableName(): string;
}
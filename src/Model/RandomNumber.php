<?php

namespace Model;

use PDO;

class RandomNumber extends Model
{
    public function create(int $number): int
    {
        $pdo = self::connectToDatabase();

        $stmt = $pdo->prepare("INSERT INTO random_numbers (number) VALUES (:number)");
        $stmt->execute(['number' => $number]);

        return $pdo->lastInsertId();
    }

    public function find(string $id): ?int
    {
        $pdo = self::connectToDatabase();
        $stmt = $pdo->prepare("SELECT number FROM random_numbers WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['number'] : null;
    }
}
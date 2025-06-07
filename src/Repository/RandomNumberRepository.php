<?php

namespace Repository;

use Core\DatabaseConnection;
use Entity\RandomNumber;
use PDO;

class RandomNumberRepository extends DatabaseConnection
{
    public function save(RandomNumber $entity): RandomNumber
    {
        $pdo = $this->getConnection();
        $stmt = $pdo->prepare("INSERT INTO random_numbers (number) VALUES (:number)");
        $stmt->execute(['number' => $entity->getNumber()]);
        $id = $pdo->lastInsertId();
        return new RandomNumber($entity->getNumber(), (int)$id);
    }

    public function find(string $id): ?RandomNumber
    {
        $pdo = $this->getConnection();
        $stmt = $pdo->prepare("SELECT id, number FROM random_numbers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new RandomNumber((int)$result['number'], (int)$result['id']) : null;
    }

}
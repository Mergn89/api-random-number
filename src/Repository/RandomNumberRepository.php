<?php

namespace Repository;

use Core\AbstractRepository;
use Entity\RandomNumber;

class RandomNumberRepository extends AbstractRepository
{
    protected string $entityClass = RandomNumber::class;
    protected array $fillable = ['number'];

    protected function getTableName(): string
    {
        return 'random_numbers';
    }
}
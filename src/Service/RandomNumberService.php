<?php

namespace Service;

use Repository\RandomNumberRepository;
use Entity\RandomNumber;

class RandomNumberService
{
    private RandomNumberRepository $repository;

    public function __construct(RandomNumberRepository $repository)
    {
        $this->repository = $repository;
    }

    public function saveNumber(int $number): RandomNumber
    {
        $entity = new RandomNumber($number);
        return $this->repository->save($entity);
    }

    public function getNumber(string $id): ?RandomNumber
    {
        return $this->repository->find($id);
    }

    public function generateNumber(): int
    {
        return rand(1, 10000);
    }
}
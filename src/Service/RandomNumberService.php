<?php

namespace Service;

use Model\RandomNumber;

class RandomNumberService
{
    private RandomNumber $randomNumber;

    public function __construct(RandomNumber $randomNumber)
    {
        $this->randomNumber = $randomNumber;
    }

    public function saveNumber(int $number): string
    {
        return $this->randomNumber->create($number);
    }

    public function getNumber(string $id): ?int
    {
        return $this->randomNumber->find($id);
    }

    public function generateNumber(): int
    {
        return rand(1, 10000);
    }
}
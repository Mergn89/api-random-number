<?php

namespace Entity;

class RandomNumber
{
    private ?int $id;
    private int $number;

    public function __construct(int $number,?int $id = null)
    {
        $this->id = $id;
        $this->number = $number;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
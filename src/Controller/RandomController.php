<?php

namespace Controller;

use Service\RandomNumberService;

class RandomController
{
    private RandomNumberService $service;

    public function __construct(RandomNumberService $service)
    {
        $this->service = $service;
    }

    public function generate(): void
    {
        $number = $this->service->generateNumber();
        $entity = $this->service->saveNumber($number);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'id' => $entity->getId(),
            'number' => $entity->getNumber()
        ]);
    }
}
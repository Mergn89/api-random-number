<?php

namespace Controller;

use Model\RandomNumber;
use Service\RandomNumberService;

class RandomController
{
    private RandomNumberService $service;

    public function __construct()
    {
        $this->service = new RandomNumberService(new RandomNumber());
    }

    public function generate(): void
    {
        $number = $this->service->generateNumber();
        $id = $this->service->saveNumber($number);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'id' => $id,
            'number' => $number
        ]);
    }
}
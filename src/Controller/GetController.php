<?php

namespace Controller;

use Model\RandomNumber;
use Service\RandomNumberService;

class GetController
{
    private RandomNumberService $service;

    public function __construct()
    {
        $this->service = new RandomNumberService(new RandomNumber());
    }

    public function get(string $id): void
    {
        $number = $this->service->getNumber($id);

        header('Content-Type: application/json');
        if ($number === null) {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'Number not found'
            ]);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'number' => $number
        ]);
    }
}
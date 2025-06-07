<?php

namespace Controller;

use Service\RandomNumberService;

class GetController
{
    private RandomNumberService $service;

    public function __construct(RandomNumberService $service)
    {
        $this->service = $service;
    }

    public function get(string $id): void
    {
        $randomNumber = $this->service->getNumber($id);

        header('Content-Type: application/json');
        if ($randomNumber === null) {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'Number not found'
            ]);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'id' => $randomNumber->getId(),
            'number' => $randomNumber->getNumber()
        ]);
    }
}
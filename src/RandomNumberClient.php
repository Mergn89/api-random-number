<?php

require __DIR__ . '/Core/Autoload.php';


class RandomNumberClient
{
    private string $baseUrl;

    public function __construct(string $baseUrl = 'http://localhost:81')
    {
        $this->baseUrl = $baseUrl;
    }

    public function generateRandomNumber(): array
    {
        $url = $this->baseUrl . '/random';
        return $this->sendRequest($url);
    }

    public function getNumberById(string $id): array
    {
        $url = $this->baseUrl . '/get/' . urlencode($id);
        return $this->sendRequest($url);
    }

    private function sendRequest(string $url): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("CURL error: $error");
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON decode error: " . json_last_error_msg());
        }

        if ($httpCode >= 400) {
            throw new Exception($data['message'] ?? 'API request failed', $httpCode);
        }

        return $data;
    }
}

try {
    $client = new RandomNumberClient();

    $result = $client->generateRandomNumber();
    echo "Сгенерированный номер с ID: " . $result['id'] . "\n";
    echo "Номер: " . $result['number'] . "\n\n";

    $id = $result['id'];
    $getResult = $client->getNumberById($id);
    echo "Номер с ID $id: " . $getResult['number'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
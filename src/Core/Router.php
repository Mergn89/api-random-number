<?php

namespace Core;

use Controller\RandomController;
use Controller\GetController;

class Router
{
    public function handleRequest(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $routes = [
            'POST /random' => [RandomController::class, 'generate'],
            'GET /get/{id}' => [GetController::class, 'get'],
        ];

        foreach ($routes as $route => $handler) {
            [$routeMethod, $routePath] = explode(' ', $route, 2);

            if ($method !== $routeMethod) {
                continue;
            }

            if ($routePath === $uri) {
                $this->callHandler($handler);
                return;
            }

            if (strpos($routePath, '{') !== false) {
                $pattern = '#^' . preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath) . '$#';
                if (preg_match($pattern, $uri, $matches)) {
                    $this->callHandler($handler, array_slice($matches, 1));
                    return;
                }
            }
        }

        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Not Found']);
    }

    private function callHandler(array $handler, array $params = []): void
    {
        [$className, $methodName] = $handler;
        $controller = new $className();
        call_user_func_array([$controller, $methodName], $params);
    }
}
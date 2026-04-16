<?php
declare(strict_types=1);
namespace App;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, bool $protected = false): self
    {
        $this->routes['GET'][$path] = ['handler' => $handler, 'protected' => $protected];
        return $this;
    }


    public function post(string $path, array $handler, bool $protected = false): self
    {
        $this->routes['POST'][$path] = ['handler' => $handler, 'protected' => $protected];
        return $this;
    }


    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $pattern => $route) {
            $regex = $this->patternToRegex($pattern);
            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);

                if ($route['protected'] && (!isset($_SESSION['userSession']) || empty($_SESSION['userSession']))) {
                    header('Location: /');
                    exit;
                }

                [$controllerClass, $action] = $route['handler'];
                $controller = new $controllerClass();
                $controller->$action(...$params);
                return;
            }
        }

        http_response_code(404);
        header('location: /not-found');
    }

    private function patternToRegex(string $pattern): string
    {
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $regex . '$#';
    }
}

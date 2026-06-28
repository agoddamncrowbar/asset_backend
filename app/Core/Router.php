<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private string $basePath = '';
    private array $currentRouteParams = [];
    private static ?Router $instance = null;
    public function setBasePath(string $basePath): void
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function get(string $uri, callable|array $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, callable|array $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function put(string $uri, callable|array $action, array $middleware = []): void
    {
        $this->addRoute('PUT', $uri, $action, $middleware);
    }

    public function patch(string $uri, callable|array $action, array $middleware = []): void
    {
        $this->addRoute('PATCH', $uri, $action, $middleware);
    }

    public function delete(string $uri, callable|array $action, array $middleware = []): void
    {
        $this->addRoute('DELETE', $uri, $action, $middleware);
    }

    private function addRoute(
        string $method,
        string $uri,
        callable|array $action,
        array $middleware = []
    ): void {
        $this->routes[$method][$uri] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        if ($this->basePath !== '') {
            $uri = preg_replace(
                '#^' . preg_quote($this->basePath, '#') . '#',
                '',
                $uri
            );
        }

        foreach ($this->routes[$method] ?? [] as $route => $definition) {

            $pattern = preg_replace(
                '/\{([^}]+)\}/',
                '([^\/]+)',
                $route
            );

            $pattern = "#^{$pattern}$#";

            if (!preg_match($pattern, $uri, $matches)) {
                continue;
            }

            array_shift($matches);
            $this->currentRouteParams = $matches;
            /*
             * =========================
             * 1. Execute Middleware
             * =========================
             */
            foreach ($definition['middleware'] as $mw) {

                // middleware format:
                // [Class]
                // OR
                // [Class, params]

                $class = $mw[0];
                $params = $mw[1] ?? null;

                if ($params !== null) {
                    $class::handle($params);
                } else {
                    $class::handle();
                }
            }

            /*
             * =========================
             * 2. Execute Controller
             * =========================
             */
            $action = $definition['action'];

            if (is_array($action)) {

                [$controller, $methodName] = $action;

                $instance = new $controller();

                $instance->$methodName(...$matches);

                return;
            }

            call_user_func_array($action, $matches);

            return;
        }

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Route not found'
        ]);
    }
    public function getRouteParams(): array
    {
        return $this->currentRouteParams;
    }

    public function getRouteParam(int $index): mixed
    {
        return $this->currentRouteParams[$index] ?? null;
    }

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function instance(): Router
    {
        if (self::$instance === null) {
            throw new \RuntimeException('Router has not been initialized.');
        }

        return self::$instance;
    }
}
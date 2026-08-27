<?php
/**
 * Минимальный роутер для ЧПУ (человекопонятных URL) — без фреймворка,
 * см. ТЗ п.1.4 (backend "самописный, без тяжёлого фреймворка").
 */

declare(strict_types=1);

final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $pattern, callable $handler): void
    {
        $this->routes['GET'][$pattern] = $handler;
    }

    public function post(string $pattern, callable $handler): void
    {
        $this->routes['POST'][$pattern] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        $handlers = $this->routes[$method] ?? [];

        foreach ($handlers as $pattern => $handler) {
            $regex = $this->compile($pattern);
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $handler($params);
                return;
            }
        }

        http_response_code(404);
        $notFound = dirname(__DIR__) . '/templates/404.php';
        if (is_file($notFound)) {
            require $notFound;
        } else {
            echo '404 Not Found';
        }
    }

    private function compile(string $pattern): string
    {
        // {slug} -> (?P<slug>[^/]+)
        $regex = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $regex . '$#u';
    }
}

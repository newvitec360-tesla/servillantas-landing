<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    public function __construct(private array $routes = []) {}

    public function dispatch(string $path, string $method = 'GET'): void
    {
        $path = '/' . trim($path, '/');
        $path = $path === '/' ? '/' : $path;

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            View::render('errors/404', ['path' => $path]);
            return;
        }

        $routeConfig = $this->routes[$method][$path];
        
        $controllerClass = $routeConfig[0];
        $controllerMethod = $routeConfig[1];
        
        // SecurityHeadersMiddleware is MANDATORY and always first
        $middlewares = [\App\Middlewares\SecurityHeadersMiddleware::class];
        
        if (isset($routeConfig['middlewares'])) {
            $middlewares = array_merge($middlewares, $routeConfig['middlewares']);
        }

        $this->runMiddlewares($middlewares, function () use ($controllerClass, $controllerMethod) {
            $controller = new $controllerClass();
            $controller->{$controllerMethod}();
        });
    }

    private function runMiddlewares(array $middlewares, callable $core): void
    {
        $next = $core;
        foreach (array_reverse($middlewares) as $middlewareClass) {
            $next = function() use ($middlewareClass, $next) {
                /** @var MiddlewareInterface $middleware */
                $middleware = new $middlewareClass();
                $middleware->handle($next);
            };
        }
        $next();
    }
}

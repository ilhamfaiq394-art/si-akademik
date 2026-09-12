<?php

// 1. Autoloading Class
spl_autoload_register(function ($class) {
    $classPath = str_replace('App\\', '', $class);
    $classPath = str_replace('\\', '/', $classPath);
    $file = __DIR__ . '/../app/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Start Session (Acara 6)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load Routes
require_once __DIR__ . '/../routes/web.php';

// 3. Ambil URI dan Method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$basePath = '/si-akademik/Acara9/public';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

// 4. Dispatcher & Execution 
$matched = false;

foreach ($routes[$method] ?? [] as $routePattern => $routeConfig) {
    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $routePattern);
    $pattern = '#^' . $pattern . '$#';

    if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches);
        
        $handler = $routeConfig['handler'] ?? $routeConfig;
        $middlewares = $routeConfig['middleware'] ?? [];

        // Eksekusi Middleware 
        foreach ($middlewares as $middlewareClass) {
            if (class_exists($middlewareClass)) {
                $middleware = new $middlewareClass();
                $middleware->handle();
            }
        }

        // Eksekusi Controller dengan Reflection (Dependency Injection Rekursif Otomatis)
        [$controllerName, $action] = $handler;
        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (class_exists($controllerClass)) {
            // Helper function internal untuk resolve dependency berantai secara otomatis
            $resolveClass = function ($className) use (&$resolveClass) {
                $refClass = new ReflectionClass($className);
                $constructor = $refClass->getConstructor();

                if (!$constructor) {
                    return new $className();
                }

                $dependencies = [];
                foreach ($constructor->getParameters() as $param) {
                    $type = $param->getType();
                    if ($type && !$type->isBuiltin()) {
                        $dependencyClassName = $type->getName();
                        // Rekursi untuk menyelesaikan dependensi di tingkat yang lebih dalam (misal: Database untuk Repository)
                        $dependencies[] = $resolveClass($dependencyClassName);
                    }
                }

                return $refClass->newInstanceArgs($dependencies);
            };

            // Resolving controller beserta semua kebutuhan dependency-nya
            $controller = $resolveClass($controllerClass);
            
            call_user_func_array([$controller, $action], $matches);
            $matched = true;
            break;
        }
    }
}

// 5. Handling 404
if (!$matched) {
    http_response_code(404);
    echo "<h1 style='text-align:center; margin-top:50px;'>404 - Halaman Tidak Ditemukan</h1>";
}
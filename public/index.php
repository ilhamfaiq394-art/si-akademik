<?php

use App\Core\Database;
use App\Repositories\MahasiswaRepository;
use App\Models\Prodi;

// 1. Autoloading Class
spl_autoload_register(function ($class) {
    $classPath = str_replace('App\\', '', $class);
    $classPath = str_replace('\\', '/', $classPath);
    $file = __DIR__ . '/../app/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load Routes
require_once __DIR__ . '/../routes/web.php';

// 3. Ambil URI dan Method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$basePath = '/si-akademik/public';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

// 4. Dispatcher & Dependency Injection
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

        // Eksekusi Controller dengan Dependency Injection
        [$controllerName, $action] = $handler;
        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (class_exists($controllerClass)) {
            if ($controllerName === 'MahasiswaController') {
                $db = new Database();
                $repo = new MahasiswaRepository($db);
                $prodiModel = new Prodi();
                $controller = new $controllerClass($repo, $prodiModel);
            } else {
                $controller = new $controllerClass();
            }

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
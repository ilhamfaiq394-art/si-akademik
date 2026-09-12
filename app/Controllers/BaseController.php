<?php
namespace App\Controllers;

class BaseController
{
    protected function view(string $viewName, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . '/../Views/' . $viewName . '.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect(string $url): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Location: ' . $url);
        exit;
    }
}
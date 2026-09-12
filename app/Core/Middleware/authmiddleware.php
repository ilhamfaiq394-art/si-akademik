<?php
namespace App\Core\Middleware;
class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cek apakah session logged_in bernilai true
        if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            // Redirect otomatis ke halaman login jika belum terautentikasi
            header('Location: /si-akademik/public/login');
            exit;
        }
    }
}
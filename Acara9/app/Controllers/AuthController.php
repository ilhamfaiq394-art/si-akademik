<?php
namespace App\Controllers;
class AuthController
{
    // Method menampilkan form login
    public function loginForm(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Jika sudah login, langsung lempar ke /mahasiswa
        if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header('Location: /si-akademik/public/mahasiswa');
            exit;
        }

        require_once __DIR__ . '/../Views/auth/login.php';
    }

    // Method memproses autentikasi (hardcoded)
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Simulasi validasi hardcoded
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';
            
            // acara6: Set Flash Message Login Sukses
            $_SESSION['flash_message'] = [
                'type'    => 'success',
                'message' => 'Selamat datang, Admin'
            ];
            header('Location: /si-akademik/public/mahasiswa');
            exit;
        } else {
            // Flash message jika login gagal
            $_SESSION['flash_message'] = [
                'type'    => 'danger',
                'message' => 'Username atau password salah!'
            ];
            header('Location: /si-akademik/public/login');
            exit;
        }
    }

    public function logout(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 1. Hapus hanya data login 
    unset($_SESSION['logged_in']);
    unset($_SESSION['user_name']);

    // 2. Set Flash Message Logout
    $_SESSION['flash_message'] = [
        'type'    => 'info',
        'message' => 'Anda telah logout'
    ];

    // 3. Redirect ke halaman login
    header('Location: /si-akademik/public/login');
    exit;
}
}
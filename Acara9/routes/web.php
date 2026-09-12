<?php

use App\Core\Middleware\AuthMiddleware;

$routes = [
    'GET' => [
        // Rute Publik
        '/login'               => ['handler' => ['AuthController', 'loginForm'], 'middleware' => []],
        '/logout'              => ['handler' => ['AuthController', 'logout'], 'middleware' => []],

       // --- MAHASISWA ---
        '/'                      => ['handler' => ['MahasiswaController', 'index'], 'middleware' => [AuthMiddleware::class]],
        '/mahasiswa'             => ['handler' => ['MahasiswaController', 'index'], 'middleware' => [AuthMiddleware::class]],
        '/mahasiswa/create'      => ['handler' => ['MahasiswaController', 'create'], 'middleware' => [AuthMiddleware::class]],
        '/mahasiswa/edit/{id}'   => ['handler' => ['MahasiswaController', 'edit'], 'middleware' => [AuthMiddleware::class]],
        '/mahasiswa/delete/{id}' => ['handler' => ['MahasiswaController', 'delete'], 'middleware' => [AuthMiddleware::class]],
        '/mahasiswa/{nim}'       => ['handler' => ['MahasiswaController', 'show'], 'middleware' => [AuthMiddleware::class]], 
        
        // --- PRODI ---
        '/prodi'               => ['handler' => ['ProdiController', 'index'], 'middleware' => [AuthMiddleware::class]],
        '/prodi/create'        => ['handler' => ['ProdiController', 'create'], 'middleware' => [AuthMiddleware::class]],
        '/prodi/edit/{id}'     => ['handler' => ['ProdiController', 'edit'], 'middleware' => [AuthMiddleware::class]],
        '/prodi/delete/{id}'   => ['handler' => ['ProdiController', 'delete'], 'middleware' => [AuthMiddleware::class]],

        // --- MATA KULIAH ---
        '/matakuliah'             => ['handler' => ['MataKuliahController', 'index'], 'middleware' => [AuthMiddleware::class]],
        '/matakuliah/create'      => ['handler' => ['MataKuliahController', 'create'], 'middleware' => [AuthMiddleware::class]],
        '/matakuliah/edit/{id}'   => ['handler' => ['MataKuliahController', 'edit'], 'middleware' => [AuthMiddleware::class]],
        '/matakuliah/delete/{id}' => ['handler' => ['MataKuliahController', 'delete'], 'middleware' => [AuthMiddleware::class]],
    ],

    'POST' => [
        // Rute Publik
        '/login'               => ['handler' => ['AuthController', 'login'], 'middleware' => []],

        // --- MAHASISWA ---
        '/mahasiswa/store'       => ['handler' => ['MahasiswaController', 'store'], 'middleware' => [AuthMiddleware::class]],
        '/mahasiswa'             => ['handler' => ['MahasiswaController', 'store'], 'middleware' => [AuthMiddleware::class]], 
        '/mahasiswa/update/{id}' => ['handler' => ['MahasiswaController', 'update'], 'middleware' => [AuthMiddleware::class]],

        // --- PRODI ---
        '/prodi/store'         => ['handler' => ['ProdiController', 'store'], 'middleware' => [AuthMiddleware::class]],
        '/prodi/update/{id}'   => ['handler' => ['ProdiController', 'update'], 'middleware' => [AuthMiddleware::class]],

        // --- MATA KULIAH ---
        '/matakuliah/store'       => ['handler' => ['MataKuliahController', 'store'], 'middleware' => [AuthMiddleware::class]],
        '/matakuliah/update/{id}' => ['handler' => ['MataKuliahController', 'update'], 'middleware' => [AuthMiddleware::class]],
    ]
];
<?php
// Impor file pendukung secara manual tanpa autoload
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

// Instansiasi Model
$mhs = new Mahasiswa();

// Menggunakan SETTER
$mhs->setNim(' E41253154 ');
$mhs->setNama('Muhammad Ilham');
$mhs->setEmail('ilham@example.com');
$mhs->setStatus('aktif');

// Menggunakan GETTER
echo "<h1>Tes OOP Lanjutan: Getter & Setter</h1>";
echo "<ul>";
echo "<li><strong>NIM:</strong> " . $mhs->getNim() . "</li>";
echo "<li><strong>Nama:</strong> " . $mhs->getNama() . "</li>";
echo "<li><strong>Email:</strong> " . $mhs->getEmail() . "</li>";
echo "<li><strong>Status:</strong> " . $mhs->getStatus() . "</li>";
echo "</ul>";
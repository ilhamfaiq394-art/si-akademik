<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Mahasiswa; // Ubah namespace ke App\Models\Mahasiswa
use PDO;

class MahasiswaRepositories
{
    private PDO $db;

    // Acara9 : Constructor Injection untuk objek Database
    public function __construct(Database $database) 
    {
        $this->db = $database->getConnection();
    }

    public function getAll(): array 
    {
        $sql = "SELECT m.*, p.nama AS nama_prodi 
                FROM mahasiswa m 
                LEFT JOIN prodi p ON m.prodi_id = p.id 
                ORDER BY m.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array 
    {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function create(Mahasiswa $mhs): bool 
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan) 
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan)"
        );
        return $stmt->execute([
            'nim'      => $mhs->getNim(),
            'nama'     => $mhs->getNama(),
            'email'    => $mhs->getEmail(),
            'prodi_id' => $mhs->getProdiId(),
            'angkatan' => $mhs->getAngkatan()
        ]);
    }

    public function update(Mahasiswa $mhs): bool 
    {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa 
             SET nim = :nim, nama = :nama, email = :email, prodi_id = :prodi_id, angkatan = :angkatan 
             WHERE id = :id"
        );
        return $stmt->execute([
            'id'       => $mhs->getId(),
            'nim'      => $mhs->getNim(),
            'nama'     => $mhs->getNama(),
            'email'    => $mhs->getEmail(),
            'prodi_id' => $mhs->getProdiId(),
            'angkatan' => $mhs->getAngkatan()
        ]);
    }

    public function delete(int $id): bool 
    {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
<?php
namespace App\Models;

use App\Core\Model;
use PDO;

// Model Prodi untuk mengelola tabel prodi
class Prodi extends Model
{
    // Ambil semua data prodi
    public function all(): array
    {
        $db = self::getDB();
        return $db->query("SELECT * FROM prodi ORDER BY id ASC")->fetchAll();
    }

    // Ambil data prodi dengan Pagination
    public function getPaginated(int $limit, int $offset): array
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM prodi ORDER BY id ASC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Hitung total seluruh baris data prodi
    public function countAll(): int
    {
        $db = self::getDB();
        return (int) $db->query("SELECT COUNT(*) FROM prodi")->fetchColumn();
    }

    // Ambil 1 data prodi berdasarkan ID
    public function find(int $id)
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM prodi WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Tambah prodi baru
    public function create(array $data): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)");
        return $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama']
        ]);
    }

    // Update data prodi
    public function update(int $id, array $data): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id");
        return $stmt->execute([
            'id'   => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama']
        ]);
    }

    // Hapus data prodi
    public function delete(int $id): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM prodi WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
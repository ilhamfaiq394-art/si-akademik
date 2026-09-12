<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class MataKuliah extends Model
{
    // Ambil semua data matakuliah
    public function all(): array
    {
        $db = self::getDB();
        return $db->query("SELECT * FROM matakuliah ORDER BY id ASC")->fetchAll();
    }

    // Ambil data matakuliah dengan Pagination
    public function getPaginated(int $limit, int $offset): array
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM matakuliah ORDER BY id ASC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Hitung total seluruh baris data matakuliah
    public function countAll(): int
    {
        $db = self::getDB();
        return (int) $db->query("SELECT COUNT(*) FROM matakuliah")->fetchColumn();
    }

    // Ambil 1 data matakuliah berdasarkan ID
    public function find(int $id)
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM matakuliah WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Tambah matakuliah baru (DITAMBAHKAN prodi_id)
    public function create(array $data): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO matakuliah (kode, nama, prodi_id, sks) VALUES (:kode, :nama, :prodi_id, :sks)");
        return $stmt->execute([
            'kode'     => $data['kode'],
            'nama'     => $data['nama'],
            'prodi_id' => $data['prodi_id'],
            'sks'      => $data['sks']
        ]);
    }

    // Update data matakuliah (DITAMBAHKAN prodi_id)
    public function update(int $id, array $data): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE matakuliah SET kode = :kode, nama = :nama, prodi_id = :prodi_id, sks = :sks WHERE id = :id");
        return $stmt->execute([
            'id'       => $id,
            'kode'     => $data['kode'],
            'nama'     => $data['nama'],
            'prodi_id' => $data['prodi_id'],
            'sks'      => $data['sks']
        ]);
    }

    // Hapus data matakuliah
    public function delete(int $id): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM matakuliah WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
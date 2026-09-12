<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Mahasiswa;
use PDO;

class MahasiswaRepository
{
    private PDO $db;

    // [ACARA 9] CONSTRUCTOR INJECTION: Menerima Koneksi Database --- -->
    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    // [ACARA 7] PAGINATION: Mengambil Data Mahasiswa Berdasarkan Limit dan Offset --- -->
    public function getPaginated(int $limit, int $offset): array
    {
        $query = "SELECT m.id, m.nim, m.nama, m.email, m.angkatan, m.status, m.prodi_id, p.nama AS prodi_nama
                  FROM mahasiswa m 
                  LEFT JOIN prodi p ON m.prodi_id = p.id 
                  ORDER BY m.nim ASC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch()) {
            // [ACARA 9] ENKAPSULASI & OBJECT COMPOSITION: Instansiasi Objek Mahasiswa --- -->
            $result[] = new Mahasiswa(
                $row['id'], $row['nim'], $row['nama'], $row['email'],
                $row['prodi_id'], $row['prodi_nama'], $row['angkatan'], $row['status']
            );
        }
        return $result;
    }

    // [ACARA 7] PENCARIAN (SEARCH) + PAGINATION --- -->
    public function searchPaginated(string $keyword, int $limit, int $offset): array
    {
        $query = "SELECT m.id, m.nim, m.nama, m.email, m.angkatan, m.status, m.prodi_id, p.nama AS prodi_nama
                  FROM mahasiswa m 
                  LEFT JOIN prodi p ON m.prodi_id = p.id 
                  WHERE m.nama LIKE :kw1 OR m.nim LIKE :kw2
                  ORDER BY m.nim ASC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindValue(':kw1', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':kw2', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch()) {
            //[ACARA 9] Menerjemahkan Baris Database ke Objek Mahasiswa --- -->
            $result[] = new Mahasiswa(
                $row['id'], $row['nim'], $row['nama'], $row['email'],
                $row['prodi_id'], $row['prodi_nama'], $row['angkatan'], $row['status']
            );
        }
        return $result;
    }

    // [ACARA 7] HITUNG TOTAL DATA MAHASISWA UNTUK PAGINATION --- -->
    public function countAll(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    }

    // [ACARA 7] HITUNG TOTAL DATA HASIL PENCARIAN UNTUK PAGINATION --- -->
    public function countSearch(string $keyword): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nama LIKE :kw1 OR nim LIKE :kw2");
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindValue(':kw1', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':kw2', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // [ACARA 8] MENCARI MAHASISWA BERDASARKAN ID (UNTUK EDIT/UPDATE) --- -->
    public function find(int $id): ?Mahasiswa
    {
        $stmt = $this->db->prepare("SELECT m.*, p.nama AS prodi_nama FROM mahasiswa m LEFT JOIN prodi p ON m.prodi_id = p.id WHERE m.id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new Mahasiswa(
            $row['id'], $row['nim'], $row['nama'], $row['email'],
            $row['prodi_id'], $row['prodi_nama'], $row['angkatan'], $row['status']
        );
    }

    // [ACARA 9] MENCARI MAHASISWA BERDASARKAN NIM (UNTUK DETAIL) --- -->
    public function findByNim(string $nim): ?Mahasiswa
    {
        $stmt = $this->db->prepare("SELECT m.*, p.nama AS prodi_nama FROM mahasiswa m LEFT JOIN prodi p ON m.prodi_id = p.id WHERE m.nim = :nim LIMIT 1");
        $stmt->execute(['nim' => $nim]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new Mahasiswa(
            $row['id'], $row['nim'], $row['nama'], $row['email'],
            $row['prodi_id'], $row['prodi_nama'], $row['angkatan'], $row['status']
        );
    }

    // [ACARA 6 & 9] TAMBAH DATA MAHASISWA (STORE) MENGGUNAKAN GETTER OBJEK --- -->
    public function create(Mahasiswa $mhs): bool
    {
        $stmt = $this->db->prepare("INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status) 
                                    VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)");
        return $stmt->execute([
            'nim'      => $mhs->getNim(),
            'nama'     => $mhs->getNama(),
            'email'    => $mhs->getEmail(),
            'prodi_id' => $mhs->getProdiId(),
            'angkatan' => $mhs->getAngkatan(),
            'status'   => $mhs->getStatus() ?? 'aktif'
        ]);
    }

    // [ACARA 8 & 9] UPDATE DATA MAHASISWA MENGGUNAKAN GETTER OBJEK --- -->
    public function update(Mahasiswa $mhs): bool
    {
        $stmt = $this->db->prepare("UPDATE mahasiswa 
                                    SET nim = :nim, nama = :nama, email = :email, prodi_id = :prodi_id, angkatan = :angkatan, status = :status 
                                    WHERE id = :id");
        return $stmt->execute([
            'id'       => $mhs->getId(),
            'nim'      => $mhs->getNim(),
            'nama'     => $mhs->getNama(),
            'email'    => $mhs->getEmail(),
            'prodi_id' => $mhs->getProdiId(),
            'angkatan' => $mhs->getAngkatan(),
            'status'   => $mhs->getStatus()
        ]);
    }

    // [ACARA 8] HAPUS DATA MAHASISWA (DELETE)
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
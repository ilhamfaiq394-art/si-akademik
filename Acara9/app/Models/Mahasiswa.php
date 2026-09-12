<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Mahasiswa extends Model
{
    // Properti private untuk Encapsulation
    private ?int $id = null;
    private string $nim = '';
    private string $nama = '';
    private string $email = '';
    private string $prodiId = '';
    private string $angkatan = '';
    private string $status = '';
    private string $prodi = '';

   // ==========================================
    // GETTER & SETTER (Prinsip OOP Lanjutan)
    // ==========================================

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getNim(): string { return $this->nim; }
    public function setNim(string $nim): void 
    { 
        $cleanNim = trim($nim);
        
        // Validasi: NIM boleh diawali huruf E/e (opsional) lalu diikuti angka
        // Contoh valid: E41253154, e41253154, atau 41253154
        if (!preg_match('/^[Ee]?[0-9]+$/', $cleanNim)) {
            throw new \InvalidArgumentException("Format NIM tidak valid. Gunakan kombinasi huruf E dan angka (contoh: E41253154).");
        }
        
        // Simpan NIM dengan huruf kapital otomatis
        $this->nim = strtoupper($cleanNim); 
    }

    public function getNama(): string { return $this->nama; }
    public function setNama(string $nama): void 
    { 
        $cleanNama = trim($nama);
        // Validasi: Nama tidak boleh kosong
        if (empty($cleanNama)) {
            throw new \InvalidArgumentException("Nama mahasiswa tidak boleh kosong.");
        }
        
        // Validasi Tambahan: Nama hanya boleh berisi huruf, spasi, dan karakter nama umum (.'-)
        if (!preg_match("/^[a-zA-Z\s\.',-]+$/", $cleanNama)) {
            throw new \InvalidArgumentException("Nama mahasiswa tidak boleh mengandung angka atau simbol khusus.");
        }

        $this->nama = $cleanNama; 
    }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void 
    { 
        $cleanEmail = trim($email);
        if (!filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Format email tidak valid.");
        }
        $this->email = $cleanEmail; 
    }

    public function getProdiId(): string { return $this->prodiId; }
    public function setProdiId(string $prodiId): void { $this->prodiId = $prodiId; }

    public function getAngkatan(): string { return $this->angkatan; }
    public function setAngkatan(string $angkatan): void { $this->angkatan = $angkatan; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    public function getProdi(): string { return $this->prodi; }
    public function setProdi(string $prodi): void { $this->prodi = $prodi; }

    /**
     * Mengambil seluruh data mahasiswa beserta nama prodi dan status dari database.
     */
    public function all(): array
    {
        $db = self::getDB();
        
        $query = "SELECT m.id, m.nim, m.nama, m.email, m.angkatan, m.status, p.nama AS prodi
                  FROM mahasiswa m 
                  JOIN prodi p ON m.prodi_id = p.id 
                  ORDER BY m.nim ASC";

        $stmt = $db->query($query);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil data mahasiswa dengan Pagination
     */
    public function getPaginated(int $limit, int $offset): array
    {
        $db = self::getDB();
        $query = "SELECT m.id, m.nim, m.nama, m.email, m.angkatan, m.status, p.nama AS prodi
                  FROM mahasiswa m 
                  JOIN prodi p ON m.prodi_id = p.id 
                  ORDER BY m.nim ASC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Hitung total seluruh baris data mahasiswa
     */
    public function countAll(): int
    {
        $db = self::getDB();
        return (int) $db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    }

    /**
     * Mengambil detail 1 data mahasiswa berdasarkan NIM.
     */
    public function findByNim(string $nim)
    {
        $db = self::getDB();
        $query = "SELECT m.nim, m.nama, m.email, m.angkatan, m.status, p.nama AS prodi
                  FROM mahasiswa m 
                  JOIN prodi p ON m.prodi_id = p.id 
                  WHERE m.nim = :nim 
                  LIMIT 1";

        $stmt = $db->prepare($query);
        $stmt->execute(['nim' => $nim]);
        return $stmt->fetch();
    }

    // Mengambil 1 data mahasiswa berdasarkan ID primary key
    public function find(int $id)
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT m.*, p.nama AS prodi 
                              FROM mahasiswa m 
                              JOIN prodi p ON m.prodi_id = p.id 
                              WHERE m.id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // CREATE Menambahkan data mahasiswa baru
    public function create(array $data): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status) 
                              VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)");
        return $stmt->execute([
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status'   => $data['status'] ?? 'aktif'
        ]);
    }

    // UPDATE Memperbarui data mahasiswa berdasarkan ID
    public function update(int $id, array $data): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE mahasiswa 
                              SET nim = :nim, nama = :nama, email = :email, prodi_id = :prodi_id, angkatan = :angkatan, status = :status 
                              WHERE id = :id");
        return $stmt->execute([
            'id'       => $id,
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status'   => $data['status']
        ]);
    }

    // DELETE Menghapus data mahasiswa dari database
    public function delete(int $id): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM mahasiswa WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Mengambil data mahasiswa berdasarkan keyword (NIM atau Nama) dengan Pagination
     */
    public function searchPaginated(string $keyword, int $limit, int $offset): array
    {
        $db = self::getDB();
        $query = "SELECT m.id, m.nim, m.nama, m.email, m.angkatan, m.status, p.nama AS prodi
                  FROM mahasiswa m 
                  JOIN prodi p ON m.prodi_id = p.id 
                  WHERE m.nama LIKE :keyword1 OR m.nim LIKE :keyword2
                  ORDER BY m.nim ASC 
                  LIMIT :limit OFFSET :offset";

        $stmt = $db->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        
        $stmt->bindValue(':keyword1', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':keyword2', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Hitung total baris hasil pencarian berdasarkan keyword
     */
    public function countSearch(string $keyword): int
    {
        $db = self::getDB();
        $query = "SELECT COUNT(*) FROM mahasiswa WHERE nama LIKE :keyword1 OR nim LIKE :keyword2";

        $stmt = $db->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        
        $stmt->bindValue(':keyword1', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':keyword2', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
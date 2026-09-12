<?php
namespace App\Controllers;

use App\Repositories\MahasiswaRepositories;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use InvalidArgumentException;
use PDOException;

class MahasiswaController
{
    private MahasiswaRepositories $repository;
    private Prodi $prodiModel;

    // Dependency Injection melalui Constructor
    public function __construct(MahasiswaRepositories $repository, Prodi $prodiModel)
    {
        $this->repository = $repository;
        $this->prodiModel = $prodiModel;
    }

    // Menampilkan daftar mahasiswa dari Database dengan fitur Search & Pagination
    public function index()
    {
        // Tangkap input pencarian dari form GET
        $keyword = trim($_GET['search'] ?? '');

        // Pengaturan Pagination
        $limit = 5; // Jumlah data per halaman
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        // Ambil data melalui MahasiswaRepository
        $dataMahasiswa = $this->repository->getAll();

        $content = __DIR__ . '/../Views/mahasiswa/list.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    // Menampilkan form tambah mahasiswa
    public function create()
    {
        // Ambil daftar prodi agar dropdown di view terisi
        $prodiList = $this->prodiModel->all();

        $content = __DIR__ . '/../Views/mahasiswa/create.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    // [ACARA 8] Detail Mahasiswa berdasarkan NIM
    public function show($nim)
    {
        $data = $this->repository->getAll();
        $mahasiswaFound = null;
        foreach ($data as $m) {
            if ($m['nim'] === $nim) {
                $mahasiswaFound = $m;
                break;
            }
        }

        if ($mahasiswaFound) {
            echo "<div style='padding: 20px; font-family: sans-serif;'>";
            echo "<h2>Detail Mahasiswa</h2>";
            echo "<p><strong>NIM:</strong> " . htmlspecialchars($mahasiswaFound['nim']) . "</p>";
            echo "<p><strong>Nama:</strong> " . htmlspecialchars($mahasiswaFound['nama']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($mahasiswaFound['email']) . "</p>";
            echo "<p><strong>Prodi:</strong> " . htmlspecialchars($mahasiswaFound['nama_prodi'] ?? '') . "</p>";
            echo "<p><strong>Angkatan:</strong> " . htmlspecialchars($mahasiswaFound['angkatan']) . "</p>";
            echo "<a href='/si-akademik/Acara9/public/mahasiswa'>&laquo; Kembali</a>";
            echo "</div>";
        } else {
            http_response_code(404);
            echo "<h1>Mahasiswa dengan NIM $nim tidak ditemukan!</h1>";
        }
    }

    // [ACARA 8 - STORE] Memproses input Form POST untuk simpan data baru
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            try {
                // Testing validasi input menggunakan Setter Model
                $mhs = new Mahasiswa();
                $mhs->setNim($_POST['nim'] ?? '');
                $mhs->setNama($_POST['nama'] ?? '');
                $mhs->setEmail($_POST['email'] ?? '');
                $mhs->setProdiId($_POST['prodi_id'] ?? '');
                $mhs->setAngkatan($_POST['angkatan'] ?? '');

                // Jika lolos validasi setter, simpan ke database via Repository
                $this->repository->create($mhs);

                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data mahasiswa berhasil disimpan!'];
                header('Location: /si-akademik/Acara9/public/mahasiswa');
                exit;

            } catch (InvalidArgumentException $e) {
                // Tangkap error validasi dari setter dan kembalikan ke form create
                $_SESSION['flash_message'] = ['type' => 'danger', 'message' => $e->getMessage()];
                header('Location: /si-akademik/Acara9/public/mahasiswa/create');
                exit;
            } catch (PDOException $e) {
                // Tangkap error database (seperti Foreign Key prodi_id)
                $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Gagal menyimpan ke database. Pastikan Pilihan Prodi sudah benar.'];
                header('Location: /si-akademik/Acara9/public/mahasiswa/create');
                exit;
            }
        }
    }

    // [ACARA 8 - EDIT] Menampilkan form edit berdasarkan ID
    public function edit($id)
    {
        $mahasiswa = $this->repository->getById((int)$id);
        // Ambil daftar prodi untuk dropdown pilihan di form edit
        $prodiList = $this->prodiModel->all();

        $content = __DIR__ . '/../Views/mahasiswa/edit.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    // [ACARA 8 - UPDATE] Memproses pembaruan data
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            try {
                // Testing validasi input menggunakan Setter Model
                $mhs = new Mahasiswa();
                $mhs->setId((int)$id);
                $mhs->setNim($_POST['nim'] ?? '');
                $mhs->setNama($_POST['nama'] ?? '');
                $mhs->setEmail($_POST['email'] ?? '');
                $mhs->setProdiId($_POST['prodi_id'] ?? '');
                $mhs->setAngkatan($_POST['angkatan'] ?? '');

                // Jika lolos validasi setter, perbarui database via Repository
                $this->repository->update($mhs);

                $_SESSION['flash_message'] = ['type' => 'warning', 'message' => 'Data mahasiswa berhasil diperbarui!'];
                header('Location: /si-akademik/Acara9/public/mahasiswa');
                exit;

            } catch (InvalidArgumentException $e) {
                // Tangkap error validasi dari setter dan kembalikan ke form edit
                $_SESSION['flash_message'] = ['type' => 'danger', 'message' => $e->getMessage()];
                header('Location: /si-akademik/Acara9/public/mahasiswa/edit/' . $id);
                exit;
            } catch (PDOException $e) {
                // Tangkap error database (seperti Foreign Key prodi_id)
                $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Gagal memperbarui database. Pastikan Pilihan Prodi sudah benar.'];
                header('Location: /si-akademik/Acara9/public/mahasiswa/edit/' . $id);
                exit;
            }
        }
    }

    // [ACARA 8 - DELETE] Memproses hapus data berdasarkan ID
    public function delete($id)
    {
        $this->repository->delete((int)$id);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Data mahasiswa berhasil dihapus!'];
        header('Location: /si-akademik/Acara9/public/mahasiswa');
        exit;
    }
}
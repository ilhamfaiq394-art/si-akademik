<?php

namespace App\Controllers;
use App\Models\Prodi;

class ProdiController
{
    private Prodi $prodiModel;

    public function __construct()
    {
        $this->prodiModel = new Prodi();
    }

    // 1. INDEX: Daftar data dengan Pagination Sederhana
    public function index()
    {
        // Pengaturan Pagination Sederhana
        $limit = 5; // Jumlah data per halaman
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        // Ambil data prodi sesuai offset & hitung total halaman
        $dataProdi = $this->prodiModel->getPaginated($limit, $offset);
        $totalData = $this->prodiModel->countAll();
        $totalPages = ceil($totalData / $limit);

        $content = __DIR__ . '/../Views/prodi/list.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    // 2. CREATE: Form Tambah
    public function create()
    {
        $content = __DIR__ . '/../Views/prodi/create.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    // 3. STORE: Proses Simpan (POST) - DITAMBAHKAN TRY-CATCH
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->prodiModel->create($_POST);

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['flash_message'] = [
                    'type' => 'success', 
                    'message' => 'Data prodi berhasil disimpan!'
                ];

                header('Location: /si-akademik/public/prodi');
                exit;

            } catch (\PDOException $e) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Cek jika error merupakan duplikasi kunci (Integrity constraint violation 1062)
                if ($e->getCode() == 23000 || (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062)) {
                    $_SESSION['flash_message'] = [
                        'type' => 'danger', 
                        'message' => 'Gagal menyimpan: Kode Prodi "' . htmlspecialchars($_POST['kode'] ?? '') . '" sudah digunakan!'
                    ];
                } else {
                    $_SESSION['flash_message'] = [
                        'type' => 'danger', 
                        'message' => 'Terjadi kesalahan database: ' . $e->getMessage()
                    ];
                }

                // Kembalikan pengguna ke form tambah
                header('Location: /si-akademik/public/prodi/create');
                exit;
            }
        }
    }

    // 4. SHOW: Detail
    public function show($id)
    {
        $prodi = $this->prodiModel->find((int)$id);
        $content = __DIR__ . '/../Views/prodi/show.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    // 5. EDIT: Form Edit
    public function edit($id)
    {
        $prodi = $this->prodiModel->find((int)$id);
        $content = __DIR__ . '/../Views/prodi/edit.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    // 6. UPDATE: Proses Update (POST)  TRY-CATCH
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->prodiModel->update((int)$id, $_POST);

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['flash_message'] = [
                    'type' => 'warning', 
                    'message' => 'Data berhasil diperbarui!'
                ];

                header('Location: /si-akademik/public/prodi');
                exit;

            } catch (\PDOException $e) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if ($e->getCode() == 23000 || (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062)) {
                    $_SESSION['flash_message'] = [
                        'type' => 'danger', 
                        'message' => 'Gagal memperbarui: Kode Prodi "' . htmlspecialchars($_POST['kode'] ?? '') . '" sudah digunakan!'
                    ];
                } else {
                    $_SESSION['flash_message'] = [
                        'type' => 'danger', 
                        'message' => 'Terjadi kesalahan database: ' . $e->getMessage()
                    ];
                }

                header('Location: /si-akademik/public/prodi/edit/' . $id);
                exit;
            }
        }
    }

    // 7. DELETE: Proses Hapus
    public function delete($id)
    {
        $this->prodiModel->delete((int)$id);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Data berhasil dihapus!'];
        header('Location: /si-akademik/public/prodi');
        exit;
    }
}
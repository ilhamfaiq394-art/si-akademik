<?php
namespace App\Controllers;

use App\Models\MataKuliah;
use App\Models\Prodi;

class MataKuliahController
{
    private MataKuliah $matakuliahModel;
    private Prodi $prodiModel;

    public function __construct()
    {
        $this->matakuliahModel = new MataKuliah();
        $this->prodiModel = new Prodi();
    }

    public function index()
    {
        // Pengaturan Pagination Sederhana
        $limit = 5; // Jumlah data per halaman
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        // Ambil data terbatas sesuai offset & hitung total halaman
        $dataMataKuliah = $this->matakuliahModel->getPaginated($limit, $offset);
        $totalData = $this->matakuliahModel->countAll();
        $totalPages = ceil($totalData / $limit);

        $content = __DIR__ . '/../Views/matakuliah/list.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    public function create()
    {
        // Ambil semua daftar prodi untuk dikirim ke dropdown view
        $prodiList = $this->prodiModel->all();

        $content = __DIR__ . '/../Views/matakuliah/create.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->matakuliahModel->create($_POST);

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data mata kuliah berhasil ditambahkan!'];

                header('Location: /si-akademik/public/matakuliah');
                exit;

            } catch (\PDOException $e) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Cek error FK (1452) atau Duplicate (1062)
                if ($e->getCode() == 23000) {
                    $_SESSION['flash_message'] = [
                        'type' => 'danger', 
                        'message' => 'Gagal menyimpan: Pastikan Program Studi dipilih dan Kode Mata Kuliah belum digunakan!'
                    ];
                } else {
                    $_SESSION['flash_message'] = [
                        'type' => 'danger', 
                        'message' => 'Terjadi kesalahan database: ' . $e->getMessage()
                    ];
                }

                header('Location: /si-akademik/public/matakuliah/create');
                exit;
            }
        }
    }

    public function edit($id)
    {
        $matakuliah = $this->matakuliahModel->find((int)$id);
        $prodiList = $this->prodiModel->all();

        $content = __DIR__ . '/../Views/matakuliah/edit.php';
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->matakuliahModel->update((int)$id, $_POST);

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['flash_message'] = ['type' => 'warning', 'message' => 'Data mata kuliah berhasil diperbarui!'];

                header('Location: /si-akademik/public/matakuliah');
                exit;

            } catch (\PDOException $e) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['flash_message'] = [
                    'type' => 'danger', 
                    'message' => 'Gagal memperbarui data: ' . $e->getMessage()
                ];

                header('Location: /si-akademik/public/matakuliah/edit/' . $id);
                exit;
            }
        }
    }

    public function delete($id)
    {
        $this->matakuliahModel->delete((int)$id);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Data mata kuliah berhasil dihapus!'];
        header('Location: /si-akademik/public/matakuliah');
        exit;
    }
}
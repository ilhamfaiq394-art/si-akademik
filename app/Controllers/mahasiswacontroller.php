<?php
namespace App\Controllers;

use App\Repositories\MahasiswaRepository;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use InvalidArgumentException;

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $mahasiswaRepo;
    private Prodi $prodiModel;

    // Constructor Injection Repository
    public function __construct(MahasiswaRepository $mahasiswaRepo, Prodi $prodiModel)
    {
        $this->mahasiswaRepo = $mahasiswaRepo;
        $this->prodiModel = $prodiModel;
    }

    // Menampilkan halaman utama daftar mahasiswa dengan fitur pencarian dan paginasi.
    public function index()
    {
        $keyword = trim($_GET['search'] ?? '');
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        if (!empty($keyword)) {
            $dataMahasiswa = $this->mahasiswaRepo->searchPaginated($keyword, $limit, $offset);
            $totalData     = $this->mahasiswaRepo->countSearch($keyword);
        } else {
            $dataMahasiswa = $this->mahasiswaRepo->getPaginated($limit, $offset);
            $totalData     = $this->mahasiswaRepo->countAll();
        }

        $totalPages = ceil($totalData / $limit);

        return $this->view('mahasiswa/list', [
            'dataMahasiswa' => $dataMahasiswa,
            'keyword' => $keyword,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalData' => $totalData
        ]);
    }

    // Menampilkan form tambah data mahasiswa baru beserta daftar prodi.
    public function create()
    {
        $prodiList = $this->prodiModel->all();
        return $this->view('mahasiswa/create', compact('prodiList'));
    }

    // Menampilkan detail informasi lengkap dari seorang mahasiswa berdasarkan NIM-nya.
    public function show($nim)
    {
        $mhs = $this->mahasiswaRepo->findByNim($nim);

        if ($mhs) {
            echo "<div style='padding: 20px; font-family: sans-serif;'>";
            echo "<h2>Detail Mahasiswa</h2>";
            echo "<p><strong>NIM:</strong> " . htmlspecialchars($mhs->getNim()) . "</p>";
            echo "<p><strong>Nama:</strong> " . htmlspecialchars($mhs->getNama()) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($mhs->getEmail()) . "</p>";
            echo "<p><strong>Prodi:</strong> " . htmlspecialchars($mhs->getProdiNama()) . "</p>";
            echo "<p><strong>Angkatan:</strong> " . htmlspecialchars($mhs->getAngkatan()) . "</p>";
            echo "<p><strong>Status:</strong> " . htmlspecialchars($mhs->getStatus()) . "</p>";
            echo "<a href='/si-akademik/public/mahasiswa'>&laquo; Kembali</a>";
            echo "</div>";
        } else {
            http_response_code(404);
            echo "<h1>Mahasiswa dengan NIM $nim tidak ditemukan!</h1>";
        }
    }

    // Memproses penyimpanan data mahasiswa baru yang dikirim melalui form POST dengan validasi model.
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();

            try {
                $mhs = new Mahasiswa();
                $mhs->setNim($_POST['nim'] ?? '');
                $mhs->setNama($_POST['nama'] ?? '');
                $mhs->setEmail($_POST['email'] ?? '');
                $mhs->setProdiId((int)($_POST['prodi_id'] ?? 0));
                $mhs->setAngkatan((int)($_POST['angkatan'] ?? 0));
                $mhs->setStatus($_POST['status'] ?? 'aktif');

                $this->mahasiswaRepo->create($mhs);

                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data mahasiswa berhasil disimpan!'];
                $this->redirect('/si-akademik/public/mahasiswa');

            } catch (InvalidArgumentException $e) {
                $_SESSION['flash_message'] = ['type' => 'danger', 'message' => $e->getMessage()];
                $this->redirect('/si-akademik/public/mahasiswa/create');
            }
        }
    }

    // Menampilkan form ubah/edit data mahasiswa berdasarkan id tertentu beserta daftar prodi.
    public function edit($id)
    {
        $mahasiswa = $this->mahasiswaRepo->find((int)$id);
        $prodiList = $this->prodiModel->all();

        return $this->view('mahasiswa/edit', compact('mahasiswa', 'prodiList'));
    }

    // Memproses pembaruan data mahasiswa yang dikirim dari form edit via method POST.
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();

            try {
                $mhs = new Mahasiswa();
                $mhs->setId((int)$id);
                $mhs->setNim($_POST['nim'] ?? '');
                $mhs->setNama($_POST['nama'] ?? '');
                $mhs->setEmail($_POST['email'] ?? '');
                $mhs->setProdiId((int)($_POST['prodi_id'] ?? 0));
                $mhs->setAngkatan((int)($_POST['angkatan'] ?? 0));
                $mhs->setStatus($_POST['status'] ?? 'aktif');

                $this->mahasiswaRepo->update($mhs);

                $_SESSION['flash_message'] = ['type' => 'warning', 'message' => 'Data mahasiswa berhasil diperbarui!'];
                $this->redirect('/si-akademik/public/mahasiswa');

            } catch (InvalidArgumentException $e) {
                $_SESSION['flash_message'] = ['type' => 'danger', 'message' => $e->getMessage()];
                $this->redirect('/si-akademik/public/mahasiswa/edit/' . $id);
            }
        }
    }

    // Menghapus data mahasiswa dari database berdasarkan parameter id.
    public function delete($id)
    {
        $this->mahasiswaRepo->delete((int)$id);

        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Data mahasiswa berhasil dihapus!'];
        $this->redirect('/si-akademik/public/mahasiswa');
    }
}
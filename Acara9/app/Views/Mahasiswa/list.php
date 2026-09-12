<!-- --- Acara 6: FLASH MESSAGE --- -->
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Mahasiswa</h2>
    <div>
        <a href="/si-akademik/Acara9/public/mahasiswa/create" class="btn btn-primary me-2">
            + Tambah Mahasiswa
        </a>
    </div>
</div>

<!-- --- FORM PENCARIAN (SEARCH) --- -->
<form action="/si-akademik/Acara9/public/mahasiswa" method="GET" class="mb-3">
    <div class="input-group" style="max-width: 400px;">
        <input type="text" 
               name="search" 
               class="form-control" 
               placeholder="Cari berdasarkan NIM atau Nama..." 
               value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
        <button class="btn btn-primary" type="submit">Cari</button>
        <?php if (!empty($_GET['search'])): ?>
            <a href="/si-akademik/Acara9/public/mahasiswa" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Prodi</th>
            <th>Angkatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($dataMahasiswa)): ?>
            <?php foreach ($dataMahasiswa as $mhs): ?>
                <tr>
                    <td><?= htmlspecialchars($mhs['nim']); ?></td>
                    <td><?= htmlspecialchars($mhs['nama']); ?></td>
                    <td><?= htmlspecialchars($mhs['email']); ?></td>
                    <td><?= htmlspecialchars($mhs['nama_prodi'] ?? $mhs['prodi'] ?? '-') ?></td >
                    <td>
                        <span class="badge bg-info text-dark">
                            <?= htmlspecialchars($mhs['angkatan']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($mhs['status'] === 'aktif'): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php elseif ($mhs['status'] === 'cuti'): ?>
                            <span class="badge bg-warning text-dark">Cuti</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Lulus</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/si-akademik/Acara9/public/mahasiswa/<?= htmlspecialchars($mhs['nim']); ?>" class="btn btn-sm btn-info text-white">Detail</a>
                        
                        <!-- [ACARA 8] Tombol Edit mengarah ke route edit dengan ID -->
                        <a href="/si-akademik/Acara9/public/mahasiswa/edit/<?= $mhs['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        
                        <!-- [ACARA 8] Tombol Hapus dengan Konfirmasi JavaScript -->
                        <a href="/si-akademik/Acara9/public/mahasiswa/delete/<?= $mhs['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa <?= htmlspecialchars($mhs['nama']); ?>?');">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center text-muted">
                    <?= !empty($_GET['search']) ? 'Data mahasiswa tidak ditemukan.' : 'Belum ada data mahasiswa.'; ?>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Tombol Pagination -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
  <?php $searchQuery = !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>
  <nav class="mt-3">
    <ul class="pagination justify-content-center">
      <!-- Tombol Previous -->
      <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
        <a class="page-link" href="?page=<?= $page - 1; ?><?= $searchQuery; ?>">Previous</a>
      </li>

      <!-- Angka Halaman -->
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
          <a class="page-link" href="?page=<?= $i; ?><?= $searchQuery; ?>"><?= $i; ?></a>
        </li>
      <?php endfor; ?>

      <!-- Tombol Next -->
      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">
        <a class="page-link" href="?page=<?= $page + 1; ?><?= $searchQuery; ?>">Next</a>
      </li>
    </ul>
  </nav>
<?php endif; ?>
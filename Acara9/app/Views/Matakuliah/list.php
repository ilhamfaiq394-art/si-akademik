<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Mata Kuliah</h2>
    <a href="/si-akademik/public/matakuliah/create" class="btn btn-primary">+ Tambah Mata Kuliah</a>
</div>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Kode MK</th>
            <th>Nama Mata Kuliah</th>
            <th>SKS</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($dataMataKuliah)): ?>
            <?php foreach ($dataMataKuliah as $mk): ?>
                <tr>
                    <td><?= htmlspecialchars($mk['id']); ?></td>
                    <td><?= htmlspecialchars($mk['kode']); ?></td>
                    <td><?= htmlspecialchars($mk['nama']); ?></td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($mk['sks']); ?> SKS</span></td>
                    <td>
                        <a href="/si-akademik/public/matakuliah/edit/<?= $mk['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/si-akademik/public/matakuliah/delete/<?= $mk['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah <?= htmlspecialchars($mk['nama']); ?>?');">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center text-muted">Belum ada data mata kuliah.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php if (isset($totalPages) && $totalPages > 1): ?>
  <nav class="mt-3">
    <ul class="pagination justify-content-center">
      <!-- Tombol Previous -->
      <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
        <a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
      </li>

      <!-- Angka Halaman -->
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
          <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
        </li>
      <?php endfor; ?>

      <!-- Tombol Next -->
      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">
        <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
      </li>
    </ul>
  </nav>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Program Studi</h2>
    <a href="/si-akademik/public/prodi/create" class="btn btn-primary">+ Tambah Prodi</a>
</div>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Kode Prodi</th>
            <th>Nama Prodi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($dataProdi)): ?>
            <?php foreach ($dataProdi as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']); ?></td>
                    <td><?= htmlspecialchars($p['kode']); ?></td>
                    <td><?= htmlspecialchars($p['nama']); ?></td>
                    <td>
                        <a href="/si-akademik/public/prodi/edit/<?= $p['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/si-akademik/public/prodi/delete/<?= $p['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus prodi <?= htmlspecialchars($p['nama']); ?>?');">
                           Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center text-muted">Belum ada data program studi.</td>
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
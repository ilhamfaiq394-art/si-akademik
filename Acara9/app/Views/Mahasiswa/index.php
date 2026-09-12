<div class="container mt-4">
    <h1 class="mb-4">Daftar Mahasiswa</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/si-akademik/public/mahasiswa/create" class="btn btn-primary">Tambah Mahasiswa</a>

        <!-- Form Pencarian -->
        <form action="/si-akademik/public/mahasiswa" method="GET" class="d-flex gap-2">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Cari NIM atau Nama..." 
                   value="<?= htmlspecialchars($keyword ?? ''); ?>">
            <button type="submit" class="btn btn-secondary">Cari</button>
            <?php if (!empty($keyword)): ?>
                <a href="/si-akademik/public/mahasiswa" class="btn btn-outline-danger">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabel Data Mahasiswa Dinamis -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th class="text-center" width="5%">No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th class="text-center">Status</th>
                <th class="text-center" width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dataMahasiswa)): ?>
                <?php 
                $no = $offset + 1;
                foreach ($dataMahasiswa as $m): 
                ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($m['nim']); ?></td>
                        <td><?= htmlspecialchars($m['nama']); ?></td>
                        <td><?= htmlspecialchars($m['prodi'] ?? '-'); ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $m['status'] === 'aktif' ? 'success' : 'secondary'; ?>">
                                <?= ucfirst($m['status']); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="/si-akademik/public/mahasiswa/edit/<?= $m['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="/si-akademik/public/mahasiswa/delete/<?= $m['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">
                        <?= !empty($keyword) ? 'Data mahasiswa dengan kata kunci tersebut tidak ditemukan.' : 'Belum ada data mahasiswa.'; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-end">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="/si-akademik/public/mahasiswa?page=<?= $i; ?><?= !empty($keyword) ? '&search=' . urlencode($keyword) : ''; ?>">
                            <?= $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
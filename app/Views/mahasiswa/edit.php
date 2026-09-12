<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark py-3">
        <h5 class="mb-0 fw-bold">Edit Data Mahasiswa</h5>
    </div>
    <div class="card-body p-4">
        <?php if (isset($mahasiswa) && $mahasiswa): ?>
            <form action="/si-akademik/public/mahasiswa/update/<?= $mahasiswa->getId(); ?>" method="POST">
                
                <div class="mb-3">
                    <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="nim" 
                           name="nim" 
                           value="<?= htmlspecialchars($mahasiswa->getNim()); ?>" 
                           required>
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control" 
                           id="nama" 
                           name="nama" 
                           value="<?= htmlspecialchars($mahasiswa->getNama()); ?>" 
                           required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           value="<?= htmlspecialchars($mahasiswa->getEmail()); ?>" 
                           required>
                </div>

                <div class="mb-3">
                    <label for="prodi_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                    <select class="form-select" id="prodi_id" name="prodi_id" required>
                        <option value="" disabled>-- Pilih Program Studi --</option>
                        <?php if (!empty($prodiList)): ?>
                            <?php foreach ($prodiList as $prodi): ?>
                                <option value="<?= $prodi['id']; ?>" <?= ($mahasiswa->getProdiId() == $prodi['id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($prodi['nama_prodi'] ?? $prodi['nama'] ?? ''); ?>
                                    <?= !empty($prodi['jenjang']) ? ' (' . htmlspecialchars($prodi['jenjang']) . ')' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="angkatan" class="form-label">Angkatan <span class="text-danger">*</span></label>
                    <input type="number" 
                           class="form-control" 
                           id="angkatan" 
                           name="angkatan" 
                           value="<?= htmlspecialchars($mahasiswa->getAngkatan()); ?>" 
                           required>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="aktif" <?= ($mahasiswa->getStatus() === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="cuti" <?= ($mahasiswa->getStatus() === 'cuti') ? 'selected' : ''; ?>>Cuti</option>
                        <option value="lulus" <?= ($mahasiswa->getStatus() === 'lulus') ? 'selected' : ''; ?>>Lulus</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="/si-akademik/public/mahasiswa" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Perbarui Data</button>
                </div>

            </form>
        <?php else: ?>
            <div class="alert alert-danger">Data mahasiswa tidak ditemukan.</div>
            <a href="/si-akademik/public/mahasiswa" class="btn btn-secondary">&laquo; Kembali</a>
        <?php endif; ?>
    </div>
</div>
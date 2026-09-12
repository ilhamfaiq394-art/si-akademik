<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark py-3">
        <h5 class="mb-0 fw-bold">Edit Mata Kuliah</h5>
    </div>
    <div class="card-body p-4">
        <form action="/si-akademik/public/matakuliah/update/<?= $matakuliah['id']; ?>" method="POST">
            
            <div class="mb-3">
                <label for="kode" class="form-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control" 
                       id="kode" 
                       name="kode" 
                       value="<?= htmlspecialchars($matakuliah['kode'] ?? ''); ?>" 
                       placeholder="Contoh: MK001" 
                       required>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control" 
                       id="nama" 
                       name="nama" 
                       value="<?= htmlspecialchars($matakuliah['nama'] ?? ''); ?>" 
                       placeholder="Contoh: Pemrograman Web" 
                       required>
            </div>

            <div class="mb-3">
                <label for="prodi_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                <select class="form-select" id="prodi_id" name="prodi_id" required>
                    <option value="" disabled>-- Pilih Program Studi --</option>
                    <?php if (!empty($prodiList)): ?>
                        <?php foreach ($prodiList as $prodi): ?>
                            <option value="<?= $prodi['id']; ?>" <?= ($matakuliah['prodi_id'] ?? '') == $prodi['id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($prodi['nama_prodi'] ?? $prodi['nama'] ?? ''); ?>
                                <?= !empty($prodi['jenjang']) ? ' (' . htmlspecialchars($prodi['jenjang']) . ')' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="sks" class="form-label">Jumlah SKS <span class="text-danger">*</span></label>
                <input type="number" 
                       class="form-control" 
                       id="sks" 
                       name="sks" 
                       min="1" 
                       max="6" 
                       value="<?= htmlspecialchars($matakuliah['sks'] ?? ''); ?>" 
                       placeholder="Contoh: 3" 
                       required>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="/si-akademik/public/matakuliah" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning text-dark fw-bold">Perbarui Data</button>
            </div>

        </form>
    </div>
</div>
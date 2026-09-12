<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0 fw-bold">Tambah Mata Kuliah</h5>
    </div>
    <div class="card-body p-4">
        <form action="/si-akademik/public/matakuliah/store" method="POST">
            <div class="mb-3">
                <label for="kode" class="form-label">Kode Mata Kuliah <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="kode" name="kode" placeholder="Contoh: MK001" required>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Pemrograman Web" required>
            </div>

            <div class="mb-3">
                <label for="prodi_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
                <select name="prodi_id" id="prodi_id" class="form-select" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <?php if (!empty($prodiList)): ?>
                        <?php foreach ($prodiList as $prodi): ?>
                            <option value="<?= $prodi['id'] ?>">
                                <?= htmlspecialchars($prodi['nama_prodi'] ?? $prodi['nama'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="sks" class="form-label">Jumlah SKS <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="sks" name="sks" min="1" max="6" placeholder="Contoh: 3" required>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="/si-akademik/public/matakuliah" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>
</div>